<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluars';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tanggal_surat'  => 'date',
            'tgl_surat'      => 'date',
            'tahun'          => 'integer',
            'nomor_urut'     => 'integer',
            'payload_detail' => 'array',
        ];
    }

    /**
     * Scope khusus untuk memfilter Arsip Surat Keluar Murni
     */
    public function scopeArsipKeluar($query)
    {
        return $query->where(function ($q) {
            $q->whereNotIn('jenis_surat', ['sppd', 'aktif_belajar', 'aktif_mengajar', 'custom'])
              ->orWhereNull('jenis_surat');
        });
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Helper Generator Nomor Surat Standar Dikdasmen Muhammadiyah.
     */
    public static function generateNomorSurat($jenisSurat)
    {
        $tahun = date('Y');
        $bulanRomawi = self::getRomawi(date('n'));

        $maxUrut = self::whereYear('tanggal_surat', $tahun)->orWhereYear('tgl_surat', $tahun)->max('nomor_urut') ?? 0;
        $nextUrut = $maxUrut + 1;
        $formattedUrut = sprintf('%03d', $nextUrut);

        if ($jenisSurat === 'sppd') {
            $nomorLengkap = "{$formattedUrut}/PPD/IV.4.AU/F/{$bulanRomawi}/{$tahun}";
        } elseif ($jenisSurat === 'aktif_belajar') {
            $nomorLengkap = "{$formattedUrut}/Ket-Aktif.S/IV.4.AU/F/{$bulanRomawi}/{$tahun}";
        } elseif ($jenisSurat === 'aktif_mengajar') {
            $nomorLengkap = "{$formattedUrut}/Ket-Aktif.G/IV.4.AU/F/{$bulanRomawi}/{$tahun}";
        } else {
            $nomorLengkap = "{$formattedUrut}/UND/IV.4.AU/F/{$bulanRomawi}/{$tahun}";
        }

        return [
            'nomor_urut' => $nextUrut,
            'nomor_surat' => $nomorLengkap,
        ];
    }

    private static function getRomawi($bln)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $map[(int)$bln] ?? 'I';
    }
}