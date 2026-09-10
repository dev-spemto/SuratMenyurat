<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturans';

    protected $fillable = [
        'nama_majelis',
        'pimpinan',
        'nama_sekolah',
        'akreditasi',
        'npsn',
        'nss',
        'alamat',
        'kode_pos',
        'kota',
        'nomor_hp',
        'email',
        'website',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'logo',
        'logo_kiri',
        'logo_kanan',
        'nama_aplikasi',
        'copyright',
    ];
}