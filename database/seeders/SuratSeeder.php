<?php

namespace Database\Seeders;

use App\Models\Surat;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            // 1. SAMPLE SPPD & SURAT TUGAS
            [
                'match_condition' => ['jenis_surat' => 'sppd', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/PPD/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -1, // Unik
                    'jenis_surat'    => 'sppd',
                    'jenis_surat_id' => 1,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Perjalanan Dinas Pendampingan Kegiatan Lomba (Master System)',
                    'tujuan'         => 'Dinas Pendidikan Kabupaten Brebes',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '1 Lembar',
                    'keterangan'     => 'Surat Tugas & SPPD Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'maksud_dinas'          => 'Pendampingan Siswa Lomba Olimpiade Sains Kabupaten',
                        'tempat_tujuan'         => 'Aula Dinas Pendidikan Brebes',
                        'tgl_berangkat'         => '2000-01-05',
                        'tgl_kembali'           => '2000-01-05',
                        'transportasi'          => 'Kendaraan Dinas / Pribadi',
                        'keterangan'            => 'Surat Tugas & SPPD Contoh Master System',
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                        'pegawai_list'          => [
                            [
                                'id'         => 1,
                                'nama'       => 'SITI NURAENI, S. Pd.',
                                'jabatan'    => 'Guru Pendamping',
                                'nip'        => '198501012010012001',
                                'unit_kerja' => 'SMP MUHAMMADIYAH TONJONG',
                            ]
                        ],
                    ]
                ]
            ],

            // 2. SAMPLE KETERANGAN AKTIF BELAJAR
            [
                'match_condition' => ['jenis_surat' => 'aktif_belajar', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/KET/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -2, // Unik
                    'jenis_surat'    => 'aktif_belajar',
                    'jenis_surat_id' => 2,
                    'bidang_id'      => 2,
                    'tahun'          => 2000,
                    'perihal'        => 'Surat Keterangan Aktif Belajar (Master System)',
                    'tujuan'         => 'ADITYA PRATAMA',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '-',
                    'keterangan'     => 'Surat Keterangan Aktif Belajar Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'siswa_id'              => 1,
                        'nama_siswa'            => 'ADITYA PRATAMA',
                        'nis'                   => '242507001',
                        'nisn'                  => '0112345671',
                        'nik'                   => '3329061205110001',
                        'ttl'                   => 'Brebes, 12 Mei 2011',
                        'jenis_kelamin'         => 'Laki-Laki',
                        'nama_ibu'              => 'SISMAWATI',
                        'nama_ayah'             => 'IMAM PURNOMO',
                        'nama_orang_tua'        => 'IMAM PURNOMO',
                        'kelas'                 => 'VII (Tujuh) A',
                        'alamat'                => 'Jl. Raya Tonjong No. 12, Kec. Tonjong, Kab. Brebes',
                        'keperluan'             => 'pip',
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                    ]
                ]
            ],

            // 3. SAMPLE KETERANGAN AKTIF MENGAJAR
            [
                'match_condition' => ['jenis_surat' => 'aktif_mengajar', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/KET-MENGAJAR/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -3, // Unik
                    'jenis_surat'    => 'aktif_mengajar',
                    'jenis_surat_id' => 2,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Surat Keterangan Aktif Mengajar (Master System)',
                    'tujuan'         => 'AHMAD FAUZI, S. Pd.',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '-',
                    'keterangan'     => 'Surat Keterangan Aktif Mengajar Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'nama_guru'             => 'AHMAD FAUZI, S. Pd.',
                        'ttl'                   => 'Brebes, 15 Agustus 1990',
                        'nuptk'                 => '1234567890123456',
                        'nrg'                   => '987654321',
                        'nbm'                   => '10928374',
                        'nik'                   => '3329061508900002',
                        'jenis_kelamin'         => 'Laki-Laki',
                        'pendidikan_terakhir'   => 'S1 Pendidikan Bahasa Indonesia',
                        'tmt'                   => '2015-07-15',
                        'alamat'                => 'Dk. Tonjong RT 02 RW 01, Kec. Tonjong, Brebes',
                        'satminkal'             => 'SMP Muhammadiyah Tonjong (Induk)',
                        'jabatan_status'        => 'Guru Tetap Yayasan',
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                    ]
                ]
            ],

            // 4. SAMPLE SURAT CUSTOM
            [
                'match_condition' => ['jenis_surat' => 'custom', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/CUSTOM/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -4, // Unik
                    'jenis_surat'    => 'custom',
                    'jenis_surat_id' => 3,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Surat Undangan Pembinaan / Custom (Master System)',
                    'tujuan'         => 'Bapak/Ibu Orang Tua / Wali Murid',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '-',
                    'keterangan'     => 'Surat Undangan Custom Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'waktu_acara'           => 'Sabtu, 08 Januari 2000 / Pukul 08.00 WIB',
                        'tempat_acara'          => 'Aula SMP Muhammadiyah Tonjong',
                        'isi_surat'             => "Dengan hormat,\nSehubungan dengan pelaksanaan program sekolah, kami mengharapkan kehadiran Bapak/Ibu Wali Murid pada acara sosialisasi kegiatan pembelajaran.\nDemikian undangan ini kami sampaikan.",
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                    ]
                ]
            ],
        ];

        foreach ($samples as $sample) {
            Surat::updateOrCreate(
                $sample['match_condition'],
                $sample['data']
            );
        }
    }
}