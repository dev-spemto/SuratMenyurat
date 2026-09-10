<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'aktif',
    ];

    /**
     * Relasi ke SuratKeluar (jika menggunakan Model SuratKeluar)
     */
    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'jenis_surat_id');
    }

    /**
     * Relasi ke Surat (jika menggunakan Model Surat)
     */
    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'jenis_surat_id');
    }
}