<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Helper Standarisasi Format Penulisan Gelar Akademik.
     */
    public static function formatGelar($nama)
    {
        if (empty($nama)) return '';

        $replacements = [
            '/\bS\.?\s*AG\b/i'   => 'S. Ag.',
            '/\bS\.?\s*PD\b/i'   => 'S. Pd.',
            '/\bS\.?\s*KOM\b/i'  => 'S. Kom.',
            '/\bS\.?\s*T\b/i'    => 'S. T.',
            '/\bS\.?\s*E\b/i'    => 'S. E.',
            '/\bS\.?\s*PSI\b/i'  => 'S. Psi.',
            '/\bS\.?\s*SOS\b/i'  => 'S. Sos.',
            '/\bS\.?\s*HUB\b/i'  => 'S. Hub.',
            '/\bM\.?\s*PD\b/i'   => 'M. Pd.',
            '/\bM\.?\s*AG\b/i'   => 'M. Ag.',
            '/\bM\.?\s*KOM\b/i'  => 'M. Kom.',
        ];

        $formatted = preg_replace(array_keys($replacements), array_values($replacements), $nama);

        return preg_replace('/\.{2,}/', '.', $formatted);
    }

    /**
     * Accessor: Otomatis merapikan penulisan gelar saat atribut nama dipanggil.
     */
    public function getNamaAttribute($value)
    {
        return self::formatGelar($value);
    }

    /**
     * Accessor: Nama lengkap dengan gelar.
     */
    public function getNamaLengkapGelarAttribute()
    {
        return $this->nama;
    }

    /**
     * Accessor: Menangkap NUPTK/NIP dari berbagai variasi nama kolom DB.
     */
    public function getNuptkDisplayAttribute()
    {
        return $this->nuptk_nip ?? $this->nuptk ?? $this->nip ?? $this->nbm ?? '-';
    }
}