<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjanjian;

class PerjanjianController extends Controller
{
    public function index()
    {
        $perjanjians = Perjanjian::orderBy('tahun', 'desc')->get();
        return view('perjanjian.index', compact('perjanjians'));
    }

    public function create()
    {
        return view('perjanjian.create');
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
            'pihak1_signature' => 'nullable|string',
            'pihak2_name' => 'nullable|string',
            'pihak2_signature' => 'nullable|string',
        ]);

        $data['indikator'] = $data['indikator'] ? json_decode($data['indikator'], true) : null;

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

        Perjanjian::create($data);

        return redirect()->route('perjanjian.index')->with('success', 'Perjanjian tersimpan');
    }
}
