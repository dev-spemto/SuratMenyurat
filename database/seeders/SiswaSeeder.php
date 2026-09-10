<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $siswas = [
            [
                'nis'            => '242507001',
                'nisn'           => '0112345671',
                'nik'            => '3329061205110001',
                'nama'           => 'ADITYA PRATAMA',
                'ttl'            => 'Brebes, 12 Mei 2011',
                'jenis_kelamin'  => 'Laki-Laki',
                'kelas'          => 'VII A',
                'nama_ayah'      => 'BAMBANG SULISTYO',
                'nama_ibu'       => 'SITI AMINAH',
                'nama_orang_tua' => 'BAMBANG SULISTYO / SITI AMINAH',
                'alamat'         => 'Jl. Raya Tonjong No. 12, Kec. Tonjong, Kab. Brebes',
            ],
            [
                'nis'            => '242507002',
                'nisn'           => '0112345672',
                'nik'            => '3329065408110002',
                'nama'           => 'AULIA RAHMAWATI',
                'ttl'            => 'Brebes, 14 Agustus 2011',
                'jenis_kelamin'  => 'Perempuan',
                'kelas'          => 'VII A',
                'nama_ayah'      => 'NUR HIDAYAT',
                'nama_ibu'       => 'FATIMAH',
                'nama_orang_tua' => 'NUR HIDAYAT / FATIMAH',
                'alamat'         => 'Dk. Linggapura RT 02/RW 01, Kec. Tonjong, Kab. Brebes',
            ],
            [
                'nis'            => '232408015',
                'nisn'           => '0102345673',
                'nik'            => '3329062002100003',
                'nama'           => 'BAGAS KURNIAWAN',
                'ttl'            => 'Brebes, 20 Februari 2010',
                'jenis_kelamin'  => 'Laki-Laki',
                'kelas'          => 'VIII B',
                'nama_ayah'      => 'SURYADI',
                'nama_ibu'       => 'WARSIH',
                'nama_orang_tua' => 'SURYADI / WARSIH',
                'alamat'         => 'Dk. Kutamendala RT 04/RW 02, Kec. Tonjong, Kab. Brebes',
            ],
            [
                'nis'            => '232408016',
                'nisn'           => '0102345674',
                'nik'            => '3329066111100004',
                'nama'           => 'CITRA LESTARI',
                'ttl'            => 'Brebes, 21 November 2010',
                'jenis_kelamin'  => 'Perempuan',
                'kelas'          => 'VIII B',
                'nama_ayah'      => 'TRIYONO',
                'nama_ibu'       => 'SRI RAHAYU',
                'nama_orang_tua' => 'TRIYONO / SRI RAHAYU',
                'alamat'         => 'Dk. Pepedan RT 01/RW 03, Kec. Tonjong, Kab. Brebes',
            ],
            [
                'nis'            => '222309030',
                'nisn'           => '0092345675',
                'nik'            => '3329061503090005',
                'nama'           => 'DANI SETIAWAN',
                'ttl'            => 'Brebes, 15 Maret 2009',
                'jenis_kelamin'  => 'Laki-Laki',
                'kelas'          => 'IX A',
                'nama_ayah'      => 'AGUS SANTOSO',
                'nama_ibu'       => 'MARYATI',
                'nama_orang_tua' => 'AGUS SANTOSO / MARYATI',
                'alamat'         => 'Dk. Purwodadi RT 03/RW 05, Kec. Tonjong, Kab. Brebes',
            ],
        ];

        foreach ($siswas as $siswa) {
            Siswa::updateOrCreate(['nisn' => $siswa['nisn']], $siswa);
        }
    }
}