<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perjanjian extends Model
{
    protected $table = 'perjanjians';
    protected $fillable = ['jabatan', 'judul', 'deskripsi', 'indikator', 'tahun', 'jenis', 'tanggal_pembuatan', 'change_mode', 'sasaran', 'bobot', 'sumber_data', 'pihak1_name', 'pihak1_signature', 'pihak1_jabatan', 'pihak1_nip', 'pihak1_pangkat', 'pihak1_golongan', 'pihak2_name', 'pihak2_signature', 'pihak2_jabatan', 'pihak2_nip', 'pihak2_pangkat', 'pihak2_golongan'];

    protected $casts = [
        'indikator' => 'array',
    ];
}
