<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'aktif',
    ];

    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class);
    }
}