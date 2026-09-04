<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat_keluars';

    // HANYA DAFTARKAN KOLOM YANG BENAR-BENAR ADA DI TABEL DATABASE
    protected $fillable = [
        'nomor_urut',
        'tahun',
        'jenis_surat_id',
        'jenis_surat',
        'bidang_id',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'tujuan',
        'lampiran',
        'payload_detail',
        'pembuat_surat',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_surat'  => 'date',
        'payload_detail' => 'array',
    ];

    // ACCESSOR VIRTUAL (HANYA BISA DIBACA, TIDAK MASUK QUERY INSERT/UPDATE)
    public function getTglSuratAttribute()
    {
        return $this->attributes['tanggal_surat'] ?? null;
    }

    public function getTujuanPenerimaAttribute()
    {
        return $this->attributes['tujuan'] ?? null;
    }

    public function getPenandatanganNamaAttribute()
    {
        return $this->payload_detail['penandatangan_nama'] ?? 'IRFAN TUNZILA, S. Ag.';
    }

    public function getPenandatanganJabatanAttribute()
    {
        return $this->payload_detail['penandatangan_jabatan'] ?? 'Kepala Sekolah';
    }
}