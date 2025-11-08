<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Perjanjian;
use PDF;

class PerjanjianController extends Controller
{
    public function index()
    {
        $perjanjians = Perjanjian::orderBy('tahun', 'desc')->get();
        return view('perjanjian.index', compact('perjanjians'));
    }

    public function create()
    {
        $perjanjians = Perjanjian::orderBy('tahun','desc')->get();
        $user = Auth::user();
        return view('perjanjian.create', compact('perjanjians', 'user'));
    }

    public function print($id)
    {
        $p = Perjanjian::findOrFail($id);
        return view('perjanjian.print', compact('p'));
    }

    public function edit($id)
    {
        $perjanjians = Perjanjian::orderBy('tahun','desc')->get();
        $user = Auth::user();
        $editing = Perjanjian::findOrFail($id);
        return view('perjanjian.create', compact('perjanjians', 'user', 'editing'));
    }

    public function update(Request $request, $id)
    {
        $p = Perjanjian::findOrFail($id);
        $data = $request->validate([
            'jabatan' => 'required|string',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'jenis' => 'nullable|in:normal,perubahan',
            'tanggal_pembuatan' => 'nullable|date',
            'change_mode' => 'nullable|in:ubah_target,ubah_perjanjian',
            'tahun' => 'nullable|digits:4',
            'pihak2_name' => 'nullable|string',
            'pihak2_nip' => 'nullable|string',
            'pihak2_pangkat' => 'nullable|string',
            'pihak2_golongan' => 'nullable|string',
            'pihak2_signature' => 'nullable|string',
            'pihak2_jabatan' => 'nullable|string',
        ]);

        // handle signature data similar to store
        if (!empty($data['pihak2_signature']) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data['pihak2_signature'])) {
            $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data['pihak2_signature']);
            $sig = str_replace(' ', '+', $sig);
            $fileName = 'signatures/perjanjian_pihak2_' . uniqid() . '.png';
            \Storage::disk('public')->put($fileName, base64_decode($sig));
            $data['pihak2_signature'] = $fileName;
        }

        $p->fill($data);
        $p->save();
        return redirect()->route('perjanjian.index')->with('success', 'Perjanjian diperbarui');
    }

    public function destroy($id)
    {
        $p = Perjanjian::findOrFail($id);
        $p->delete();
        return redirect()->route('perjanjian.index')->with('success', 'Perjanjian dihapus');
    }

    // Generate PDF (requires barryvdh/laravel-dompdf)
    public function pdf($id)
    {
        $p = Perjanjian::findOrFail($id);
        try {
            $pdf = PDF::loadView('perjanjian.print', ['p' => $p])->setPaper('a4', 'portrait');
            return $pdf->stream('perjanjian_' . $p->id . '.pdf');
        } catch (\Exception $e) {
            // If PDF package not installed, show print view as fallback
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return view('perjanjian.print', compact('p'));
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jabatan' => 'required|string',
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'jenis' => 'nullable|in:normal,perubahan',
            'tanggal_pembuatan' => 'nullable|date',
            'change_mode' => 'nullable|in:ubah_target,ubah_perjanjian',
            'indikator' => 'nullable|string',
            'tahun' => 'nullable|digits:4',
            'sasaran' => 'nullable|string',
            'bobot' => 'nullable|numeric',
            'sumber_data' => 'nullable|string',
            'pihak1_name' => 'nullable|string',
            'pihak1_nip' => 'nullable|string',
            'pihak1_pangkat' => 'nullable|string',
            'pihak1_golongan' => 'nullable|string',
            'pihak1_signature' => 'nullable|string',
            'pihak1_jabatan' => 'nullable|string',
            'pihak2_name' => 'nullable|string',
            'pihak2_nip' => 'nullable|string',
            'pihak2_pangkat' => 'nullable|string',
            'pihak2_golongan' => 'nullable|string',
            'pihak2_signature' => 'nullable|string',
            'pihak2_jabatan' => 'nullable|string',
        ]);

    // indikator may be missing from the request (we disabled it in the form)
    $data['indikator'] = (isset($data['indikator']) && $data['indikator']) ? json_decode($data['indikator'], true) : null;

        // fallback: use authenticated user's info for pihak1 if not provided
        $user = Auth::user();
        if ($user) {
            if (empty($data['pihak1_name'])) {
                $data['pihak1_name'] = $user->nama ?? $user->name ?? null;
            }
            if (empty($data['pihak1_signature']) && !empty($user->tanda_tangan)) {
                // If user has a stored signature (path or URL), use it
                $data['pihak1_signature'] = $user->tanda_tangan;
            }
            if (empty($data['pihak1_jabatan']) && !empty($user->jabatan)) {
                $data['pihak1_jabatan'] = $user->jabatan;
            }
            if (empty($data['pihak1_nip']) && !empty($user->nip)) {
                $data['pihak1_nip'] = $user->nip;
            }
            if (empty($data['pihak1_pangkat']) && !empty($user->pangkat)) {
                $data['pihak1_pangkat'] = $user->pangkat;
            }
            if (empty($data['pihak1_golongan']) && !empty($user->golongan)) {
                $data['pihak1_golongan'] = $user->golongan;
            }
        }

        // normalize tanggal_pembuatan
        if (!empty($data['tanggal_pembuatan'])) {
            $data['tanggal_pembuatan'] = date('Y-m-d', strtotime($data['tanggal_pembuatan']));
        } else {
            $data['tanggal_pembuatan'] = null;
        }


        // Handle signatures (base64) - save to storage and replace with path
        if (!empty($data['pihak1_signature']) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data['pihak1_signature'])) {
            $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data['pihak1_signature']);
            $sig = str_replace(' ', '+', $sig);
            $fileName = 'signatures/perjanjian_pihak1_' . uniqid() . '.png';
            \Storage::disk('public')->put($fileName, base64_decode($sig));
            $data['pihak1_signature'] = $fileName;
        }

        if (!empty($data['pihak2_signature']) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data['pihak2_signature'])) {
            $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data['pihak2_signature']);
            $sig = str_replace(' ', '+', $sig);
            $fileName = 'signatures/perjanjian_pihak2_' . uniqid() . '.png';
            \Storage::disk('public')->put($fileName, base64_decode($sig));
            $data['pihak2_signature'] = $fileName;
        }

        // Ensure we only attempt to write columns that actually exist in DB
        try {
            $columns = Schema::getColumnListing((new Perjanjian)->getTable());
            $data = array_intersect_key($data, array_flip($columns));
        } catch (\Exception $e) {
            // If schema listing fails, proceed with original data (will likely throw DB error later)
        }

        // Determine existing perjanjian: prefer explicit id from form, else match jabatan+tahun
        $existing = null;
        if ($request->filled('existing_perjanjian_id')) {
            $existing = Perjanjian::find($request->input('existing_perjanjian_id'));
        }
        if (!$existing && !empty($data['jabatan']) && !empty($data['tahun'])) {
            $existing = Perjanjian::where('jabatan', $data['jabatan'])
                        ->where('tahun', $data['tahun'])
                        ->first();
        }

        if ($existing) {
            // Merge indikator arrays
            $existingIndik = is_array($existing->indikator) ? $existing->indikator : (json_decode($existing->indikator ?? '[]', true) ?: []);
            $newIndik = is_array($data['indikator']) ? $data['indikator'] : ($data['indikator'] ? json_decode($data['indikator'], true) : []);
            $merged = array_values(array_merge($existingIndik, $newIndik));
            $existing->indikator = $merged;

            // Update other provided fields (judul, deskripsi, pihak signatures/names if provided)
            foreach (['judul','deskripsi','pihak1_name','pihak1_nip','pihak1_pangkat','pihak1_golongan','pihak1_signature','pihak1_jabatan','pihak2_name','pihak2_nip','pihak2_pangkat','pihak2_golongan','pihak2_signature','pihak2_jabatan','jenis','tanggal_pembuatan','change_mode'] as $fld) {
                if (array_key_exists($fld, $data) && $data[$fld] !== null) {
                    $existing->{$fld} = $data[$fld];
                }
            }

            $existing->save();
        } else {
            // No existing perjanjian found — create a new one
            Perjanjian::create($data);
        }

        return redirect()->route('perjanjian.index')->with('success', 'Perjanjian tersimpan');
    }
}
