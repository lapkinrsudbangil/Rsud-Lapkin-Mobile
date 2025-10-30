<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'nama_lengkap' => 'nullable|string|max:255',
            'id_pegawai' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'pangkat' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature_data' => 'nullable|string', // tanda tangan dari canvas
        ]);

        // Simpan data teks
        $user->update($request->except(['foto_profil', 'signature_data']));

        // Upload foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            // hapus foto lama (jika ada)
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('profil', 'public');
            $user->foto_profil = $path;
        }

        // Simpan tanda tangan dari canvas (base64)
        if ($request->signature_data) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_data);
            $image = str_replace(' ', '+', $image);
            $fileName = 'tanda_tangan/' . uniqid() . '.png';
            Storage::disk('public')->put($fileName, base64_decode($image));
            $user->tanda_tangan = $fileName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
