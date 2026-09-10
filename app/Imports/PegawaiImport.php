<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PegawaiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Reset/kosongkan tabel pegawai agar ID terbuat berurutan dari baris 1 Excel
        Pegawai::truncate();

        // Helper konversi tanggal Excel
        $formatDate = function($val) {
            if (empty($val)) return null;
            if (is_numeric($val)) {
                try {
                    return Date::excelToDateTimeObject($val)->format('Y-m-d');
                } catch (\Exception $e) {
                    return $val;
                }
            }
            return trim($val);
        };

        foreach ($rows as $row) {
            $nama = $row['nama'] ?? $row['nama_lengkap'] ?? $row['nama_pegawai'] ?? null;
            if (empty($nama)) continue;

            $namaClean = trim($nama);

            $nik   = $row['nik'] ?? $row['no_ktp'] ?? null;
            $nuptk = $row['nuptk'] ?? null;
            $nip   = $row['nip'] ?? null;
            $nrg   = $row['nrg'] ?? null;
            $nbm   = $row['nbm'] ?? $row['ktam'] ?? null;
            $ttl   = $row['ttl'] ?? $row['tempat_tanggal_lahir'] ?? null;
            $jk    = $row['jenis_kelamin'] ?? $row['jk'] ?? $row['l_p'] ?? null;
            $pend  = $row['pendidikan_terakhir'] ?? $row['pendidikan'] ?? $row['kualifikasi'] ?? null;
            $tmt   = $formatDate($row['tmt'] ?? $row['tgl_mulai_tugas'] ?? $row['tmt_pengangkatan'] ?? null);
            $jbt   = $row['jabatan'] ?? $row['tugas_tambahan'] ?? 'Guru';
            $sat   = $row['satminkal'] ?? $row['sekolah_induk'] ?? 'SMP Muhammadiyah Tonjong (Induk)';
            $almt  = $row['alamat'] ?? $row['alamat_rumah'] ?? null;

            // Logika koreksi NUPTK / NIP
            if (empty($nuptk) && !empty($nip) && strlen(preg_replace('/\D/', '', $nip)) === 16) {
                $nuptk = $nip;
                $nip = null;
            }

            // Normalisasi JK
            if ($jk) {
                $jkClean = strtoupper(trim($jk));
                if (in_array($jkClean, ['L', 'LAKI-LAKI', 'LAKI LAKI'])) $jk = 'Laki-Laki';
                if (in_array($jkClean, ['P', 'PEREMPUAN'])) $jk = 'Perempuan';
            }

            Pegawai::create([
                'nama'                => $namaClean,
                'nik'                 => $nik ? (string)$nik : null,
                'nuptk'               => $nuptk ? (string)$nuptk : null,
                'nip'                 => $nip ? (string)$nip : null,
                'nrg'                 => $nrg ? (string)$nrg : null,
                'nbm'                 => $nbm ? (string)$nbm : null,
                'ttl'                 => $ttl,
                'jenis_kelamin'       => $jk,
                'pendidikan_terakhir' => $pend,
                'tmt'                 => $tmt,
                'jabatan'             => $jbt,
                'satminkal'           => $sat,
                'alamat'              => $almt,
            ]);
        }
    }
}