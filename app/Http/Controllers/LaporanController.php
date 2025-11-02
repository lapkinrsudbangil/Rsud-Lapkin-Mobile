<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perjanjian;
use App\Models\Laporan;


class LaporanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jabatan = $user->jabatan ?? null;

        $perjanjians = Perjanjian::where('jabatan', $jabatan)->orderBy('tahun', 'desc')->get();

        return view('laporan.index', compact('perjanjians'));
    }

    public function createFromPerjanjian($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        // Render form prefilled using perjanjian
        return view('laporan.create', compact('perjanjian'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'perjanjian_id' => 'nullable|exists:perjanjians,id',
            'periode_awal' => 'nullable|string',
            'periode_akhir' => 'nullable|string',
            'uraian_kegiatan' => 'required|string',
            'indikator' => 'nullable|string',
            'target' => 'nullable|numeric',
            'realisasi' => 'nullable|numeric',
            'satuan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'sasaran' => 'nullable|string',
            'bobot' => 'nullable|numeric',
            'sumber_data' => 'nullable|string',
            'pihak1_name' => 'nullable|string',
            'pihak1_signature' => 'nullable|string',
            'pihak2_name' => 'nullable|string',
            'pihak2_signature' => 'nullable|string',
        ]);

        $data['user_id'] = $user->id;
        $data['indikator'] = $data['indikator'] ? json_decode($data['indikator'], true) : null;

        // calculate persentase if possible
        if (isset($data['target']) && isset($data['realisasi']) && $data['target'] != 0) {
            $data['persentase'] = round(($data['realisasi'] / $data['target']) * 100, 2);
        }

        // If perjanjian exists and sasaran/bobot not provided, copy from perjanjian
        if (!empty($data['perjanjian_id'])) {
            $pj = Perjanjian::find($data['perjanjian_id']);
            if ($pj) {
                if (empty($data['sasaran'])) $data['sasaran'] = $pj->sasaran;
                if (empty($data['bobot'])) $data['bobot'] = $pj->bobot;
                if (empty($data['sumber_data'])) $data['sumber_data'] = $pj->sumber_data;
            }
        }

        // Handle signatures (base64) for pihak1 and pihak2
        if (!empty($data['pihak1_signature']) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data['pihak1_signature'])) {
            $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data['pihak1_signature']);
            $sig = str_replace(' ', '+', $sig);
            $fileName = 'signatures/laporan_pihak1_' . uniqid() . '.png';
            \Storage::disk('public')->put($fileName, base64_decode($sig));
            $data['pihak1_signature'] = $fileName;
        }

        if (!empty($data['pihak2_signature']) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $data['pihak2_signature'])) {
            $sig = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $data['pihak2_signature']);
            $sig = str_replace(' ', '+', $sig);
            $fileName = 'signatures/laporan_pihak2_' . uniqid() . '.png';
            \Storage::disk('public')->put($fileName, base64_decode($sig));
            $data['pihak2_signature'] = $fileName;
        }

        $laporan = Laporan::create($data);

    return redirect()->route('laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }

    // show user's laporan
    public function myReports()
    {
        $user = Auth::user();
        $laporans = Laporan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('laporan.my_reports', compact('laporans'));
    }
}
