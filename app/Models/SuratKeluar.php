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
     * ACCESSOR VIRTUAL
     */

    // PENGUNCI MASTER SYSTEM (Sampel bawaan selalu dilindungi dari Edit/Delete)
    public function getIsMasterSampleAttribute(): bool
    {
        return $this->tahun == 2000 || $this->nomor_urut <= 0;
    }

    /**
     * Scope khusus untuk memfilter Arsip Surat Keluar Murni (Mengecualikan semua dokumen generator dinamis)
     */
    public function scopeArsipKeluar($query)
    {
        return $query->where(function ($q) {
            $q->whereNotIn('jenis_surat', ['sppd', 'aktif_belajar', 'aktif_mengajar', 'und', 'pbh', 'pmh', 'custom'])
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

        // Ambil nomor urut tertinggi hanya untuk tahun berjalan
        $maxUrut = self::whereYear('tanggal_surat', $tahun)
            ->orWhereYear('tgl_surat', $tahun)
            ->where('nomor_urut', '>', 0) // Abaikan nomor urut sampel master system (< 0)
            ->max('nomor_urut') ?? 0;

        $nextUrut = $maxUrut + 1;
        $formattedUrut = sprintf('%03d', $nextUrut);

        // Mapping Kode Klasifikasi Surat
        $kodeMap = [
            'sppd'          => 'PPD',
            'aktif_belajar' => 'Ket-Aktif.S',
            'aktif_mengajar' => 'Ket-Aktif.G',
            'und'           => 'UND',
            'pbh'           => 'PBH',
            'pmh'           => 'PMH',
            'custom'        => 'CUSTOM',
        ];

        $kodeSurat = $kodeMap[strtolower($jenisSurat)] ?? strtoupper($jenisSurat);
        $nomorLengkap = "{$formattedUrut}/{$kodeSurat}/IV.4.AU/F/{$bulanRomawi}/{$tahun}";

        return [
            'nomor_urut'  => $nextUrut,
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