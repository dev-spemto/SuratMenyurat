<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_urut',
        'tahun',
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

    protected $casts = [
        'tanggal_surat'  => 'date',
        'tanggal_terima' => 'date',
    ];

    /**
     * Relasi ke JenisSurat
     */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Accessor untuk mendeteksi apakah dokumen adalah sampel bawaan Master System
     */
    public function getIsMasterSampleAttribute(): bool
    {
        return (int)$this->tahun === 2000 
            || (isset($this->nomor_urut) && (int)$this->nomor_urut <= 0)
            || $this->nomor_surat === '000/SMP/SAMPLE/2000';
    }
}