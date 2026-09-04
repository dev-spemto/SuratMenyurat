<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Melewati baris jika nama siswa kosong
        if (empty($row['nama'])) {
            return null;
        }

        return Siswa::updateOrCreate(
            ['nisn' => $row['nisn'] ?? $row['nis']], // Kunci unik untuk pencegahan duplikasi
            [
                'nis'            => $row['nis'] ?? null,
                'nik'            => $row['nik'] ?? null,
                'nama'           => $row['nama'],
                'ttl'            => $row['ttl'] ?? $row['tempat_tanggal_lahir'] ?? null,
                'jenis_kelamin'  => $row['jenis_kelamin'] ?? $row['jk'] ?? null,
                'kelas'          => $row['kelas'] ?? null,
                'nama_orang_tua' => $row['nama_orang_tua'] ?? $row['orang_tua'] ?? $row['nama_ayah'] ?? null,
                'alamat'         => $row['alamat'] ?? null,
            ]
        );
    }
}