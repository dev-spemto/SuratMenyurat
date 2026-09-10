<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Reset tabel siswa
        Siswa::truncate();

        foreach ($rows as $row) {
            if (empty($row['nama'])) continue;

            $keyIdentifier = $row['nisn'] ?? $row['nis'] ?? null;

            $namaAyah = $row['nama_ayah'] ?? $row['ayah'] ?? null;
            $namaIbu  = $row['nama_ibu'] ?? $row['ibu'] ?? null;
            $namaOrtu = $row['nama_orang_tua'] ?? $row['orang_tua'] ?? null;

            if (empty($namaOrtu) && ($namaAyah || $namaIbu)) {
                $ortuArr = array_filter([$namaAyah, $namaIbu]);
                $namaOrtu = implode(' / ', $ortuArr);
            }

            Siswa::create([
                'nis'            => $row['nis'] ?? null,
                'nisn'           => $keyIdentifier,
                'nik'            => $row['nik'] ?? null,
                'nama'           => $row['nama'],
                'ttl'            => $row['ttl'] ?? $row['tempat_tanggal_lahir'] ?? null,
                'jenis_kelamin'  => $row['jenis_kelamin'] ?? $row['jk'] ?? null,
                'kelas'          => $row['kelas'] ?? null,
                'nama_ayah'      => $namaAyah,
                'nama_ibu'       => $namaIbu,
                'nama_orang_tua' => $namaOrtu,
                'alamat'         => $row['alamat'] ?? null,
            ]);
        }
    }
}