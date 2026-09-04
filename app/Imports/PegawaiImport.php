<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Melewati baris jika nama pegawai/guru kosong
        if (empty($row['nama'])) {
            return null;
        }

        // Menentukan kunci unik untuk pencegahan duplikasi data
        $keyIdentifier = $row['nip'] ?? $row['nuptk'] ?? $row['nik'] ?? $row['nama'];

        return Pegawai::updateOrCreate(
            ['nama' => $row['nama']], 
            [
                'nuptk'               => $row['nuptk'] ?? null,
                'nip'                 => $row['nip'] ?? null,
                'nrg'                 => $row['nrg'] ?? null,
                'nbm'                 => $row['nbm'] ?? null,
                'nik'                 => $row['nik'] ?? null,
                'ttl'                 => $row['ttl'] ?? $row['tempat_tanggal_lahir'] ?? null,
                'jenis_kelamin'       => $row['jenis_kelamin'] ?? $row['jk'] ?? null,
                'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? $row['pendidikan'] ?? null,
                'tmt'                 => $row['tmt'] ?? null,
                'alamat'              => $row['alamat'] ?? null,
                'satminkal'           => $row['satminkal'] ?? 'SMP MUHAMMADIYAH TONJONG',
                'jabatan'             => $row['jabatan'] ?? 'Guru',
            ]
        );
    }
}