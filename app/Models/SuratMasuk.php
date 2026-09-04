<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
    'nomor_surat',
    'pengirim',
    'jenis_surat_id',
    'tanggal_surat',
    'tanggal_terima',
    'perihal',
    'penerima_surat',
    'keterangan',
    'file_surat',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];
}