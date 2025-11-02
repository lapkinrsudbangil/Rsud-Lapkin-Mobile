<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return view('profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'id_pegawai' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'pangkat' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'croppedPhotoData' => 'nullable|string',
            'signature_data' => 'nullable|string', // tanda tangan dari canvas
        ]);

        try {
            // Simpan data teks (kecuali file/base64 fields)
            $user->update($request->except(['foto_profil', 'signature_data', 'croppedPhotoData']));

            // Upload foto profil jika ada (file input)
            if ($request->hasFile('foto_profil')) {
                // hapus foto lama (jika ada)
                if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }

                $path = $request->file('foto_profil')->store('profil', 'public');
                $user->foto_profil = $path;
            }

            // Jika Croppie mengirimkan base64 (cropped image)
            if ($request->filled('croppedPhotoData')) {
                // contoh: data:image/jpeg;base64,/... atau data:image/png;base64,/...
                $data = $request->input('croppedPhotoData');
                if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data, $matches)) {
                    $type = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                    $data = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data);
                    $data = str_replace(' ', '+', $data);

                    // hapus foto lama
                    if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                        Storage::disk('public')->delete($user->foto_profil);
                    }

                    $fileName = 'profil/' . uniqid() . '.' . $type;
                    Storage::disk('public')->put($fileName, base64_decode($data));
                    $user->foto_profil = $fileName;
                }
            }

            // Simpan tanda tangan dari canvas (base64)
            if ($request->filled('signature_data')) {
                $sig = $request->input('signature_data');
                if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $sig)) {
                    $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $sig);
                    $sig = str_replace(' ', '+', $sig);
                    $binary = base64_decode($sig);

                    // Try upload to Supabase Storage if configured
                    $supabaseUrl = env('SUPABASE_URL');
                    $supabaseServiceKey = env('SUPABASE_SERVICE_ROLE_KEY');
                    $supabaseAnonKey = env('SUPABASE_ANON_KEY');
                    $bucket = env('SUPABASE_STORAGE_BUCKET', 'public');

                    $remotePath = 'signatures/profil_' . ($user->id ?? 'guest') . '_' . uniqid() . '.png';

                    $uploadedToSupabase = false;
                    if ($supabaseUrl && $supabaseServiceKey) {
                        try {
                            $uploadUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/' . $bucket . '/' . $remotePath;

                            $response = Http::withHeaders([
                                'Authorization' => 'Bearer ' . $supabaseServiceKey,
                                'apikey' => $supabaseAnonKey ?: '',
                                'Content-Type' => 'image/png',
                            ])->withBody($binary, 'image/png')->put($uploadUrl);

                            if ($response->successful()) {
                                $publicUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/' . $bucket . '/' . $remotePath;

                                // Optionally delete old remote file if stored as a full URL of supabase public path
                                if (!empty($user->tanda_tangan) && str_starts_with($user->tanda_tangan, rtrim($supabaseUrl, '/'))) {
                                    // try deleting old object (best-effort)
                                    try {
                                        $oldPath = str_replace(rtrim($supabaseUrl, '/'), '', $user->tanda_tangan);
                                        $oldPath = ltrim($oldPath, '/storage/v1/object/public/');
                                        // Supabase deletion endpoint: DELETE /storage/v1/object/{bucket}/{path}
                                        $delUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/' . $bucket . '/' . $oldPath;
                                        Http::withHeaders([
                                            'Authorization' => 'Bearer ' . $supabaseServiceKey,
                                            'apikey' => $supabaseAnonKey ?: '',
                                        ])->delete($delUrl);
                                    } catch (\Exception $ee) {
                                        // ignore delete errors
                                    }
                                }

                                $user->tanda_tangan = $publicUrl;
                                $uploadedToSupabase = true;
                            } else {
                                // log failure
                                \Log::warning('Supabase upload failed', [
                                    'status' => $response->status(),
                                    'body' => $response->body(),
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Supabase upload error: ' . $e->getMessage());
                        }
                    }

                    // Fallback: save to local public storage
                    if (! $uploadedToSupabase) {
                        // hapus tanda tangan lama lokal
                        if ($user->tanda_tangan && Storage::disk('public')->exists($user->tanda_tangan)) {
                            Storage::disk('public')->delete($user->tanda_tangan);
                        }

                        $fileName = 'tanda_tangan/' . uniqid() . '.png';
                        Storage::disk('public')->put($fileName, $binary);
                        $user->tanda_tangan = $fileName;
                    }
                }
            }

            $user->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            // Log error dan kembalikan pesan ke user
            \Log::error('Profile update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'input' => array_keys($request->all())
            ]);

            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan profil.']);
        }
    }
}
