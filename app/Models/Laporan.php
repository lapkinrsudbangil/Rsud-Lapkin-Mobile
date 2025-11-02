<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id', 'perjanjian_id', 'periode_awal', 'periode_akhir', 'uraian_kegiatan', 'indikator', 'target', 'realisasi', 'persentase', 'satuan', 'keterangan', 'sasaran', 'bobot', 'sumber_data', 'pihak1_name', 'pihak1_signature', 'pihak2_name', 'pihak2_signature'
    ];

    protected $casts = [
        'indikator' => 'array',
    ];

    public function perjanjian()
    {
        return $this->belongsTo(Perjanjian::class, 'perjanjian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
