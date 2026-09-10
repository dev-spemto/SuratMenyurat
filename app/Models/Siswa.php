<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Accessor pintar untuk menampilkan Nama Ayah & Nama Ibu
     */
    public function getNamaOrangTuaDisplayAttribute()
    {
        $ayah = trim($this->nama_ayah ?? '');
        $ibu  = trim($this->nama_ibu ?? '');

        // 1. Jika nama_ayah dan nama_ibu terisi di database
        if (!empty($ayah) && !empty($ibu)) {
            return "{$ayah} / {$ibu}";
        }

        if (!empty($ayah)) return $ayah;
        if (!empty($ibu))  return $ibu;

        // 2. Fallback: Jika data berada di kolom nama_orang_tua
        $ortu = trim($this->nama_orang_tua ?? '');
        if (!empty($ortu) && $ortu !== '-') {
            return $ortu;
        }

        return '-';
    }
}