<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perjanjian extends Model
{
    protected $table = 'perjanjians';
    protected $fillable = ['jabatan', 'judul', 'deskripsi', 'indikator', 'tahun', 'jenis', 'tanggal_pembuatan', 'change_mode', 'sasaran', 'bobot', 'sumber_data', 'pihak1_name', 'pihak1_signature', 'pihak1_jabatan', 'pihak2_name', 'pihak2_signature', 'pihak2_jabatan'];

    protected $casts = [
        'indikator' => 'array',
    ];
}
