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
                    'nomor_urut'     => -1,
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
                    'nomor_urut'     => -2,
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
                    'nomor_urut'     => -3,
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

            // 4. SAMPLE SURAT UNDANGAN (UND)
            [
                'match_condition' => ['jenis_surat' => 'und', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/UND/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -4,
                    'jenis_surat'    => 'und',
                    'jenis_surat_id' => 1,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Undangan Rapat Evaluasi Pembelajaran (Master System)',
                    'tujuan'         => 'Bapak/Ibu Guru dan Karyawan',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '-',
                    'keterangan'     => 'Surat Undangan Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'tempat_penerima'       => 'di - Tempat',
                        'salam_pembuka'         => "Assalamu'alaikum Wr. Wb.",
                        'paragraf_pembuka'      => "Ba'da salam, teriring doa semoga Bapak/Ibu senantiasa dalam keadaan sehat wal'afiat serta sukses dalam menjalankan aktivitas sehari-hari.",
                        'hari_tanggal'          => 'Sabtu, 15 Januari 2000',
                        'waktu_acara'           => '08.00 WIB - Selesai',
                        'tempat_acara'          => 'Ruang Rapat SMP Muhammadiyah Tonjong',
                        'agenda_acara'          => 'Rapat Evaluasi Pembelajaran & Awal Semester',
                        'keterangan_acara'      => 'Mengingat pentingnya acara, dimohon hadir tepat waktu.',
                        'paragraf_penutup'      => 'Demikian surat undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.',
                        'salam_penutup'         => "Wassalamu'alaikum Wr. Wb.",
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                        'penandatangan_nip'     => '-',
                        'tembusan_list'         => ['Arsip Sekolah']
                    ]
                ]
            ],

            // 5. SAMPLE SURAT PEMBERITAHUAN (PBH)
            [
                'match_condition' => ['jenis_surat' => 'pbh', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/PBH/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -5,
                    'jenis_surat'    => 'pbh',
                    'jenis_surat_id' => 1,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Pemberitahuan Pelaksanaan Ujian Tengah Semester (Master System)',
                    'tujuan'         => 'Bapak/Ibu Orang Tua / Wali Murid',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '1 Lembar Schedule',
                    'keterangan'     => 'Surat Pemberitahuan Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'tempat_penerima'       => 'di - Tempat',
                        'salam_pembuka'         => "Assalamu'alaikum Wr. Wb.",
                        'paragraf_pembuka'      => 'Diberitahukan dengan hormat kepada Bapak/Ibu Orang Tua/Wali Murid bahwa pelaksanaan Ujian Tengah Semester akan dilaksanakan sesuai jadwal terlampir.',
                        'hari_tanggal'          => 'Senin - Sabtu, 20-25 Januari 2000',
                        'waktu_acara'           => '07.30 WIB - Selesai',
                        'tempat_acara'          => 'Gedung SMP Muhammadiyah Tonjong',
                        'agenda_acara'          => 'Pelaksanaan Ujian Tengah Semester Genap',
                        'keterangan_acara'      => 'Mohon membimbing putra/putrinya dalam belajar di rumah.',
                        'paragraf_penutup'      => 'Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.',
                        'salam_penutup'         => "Wassalamu'alaikum Wr. Wb.",
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                        'penandatangan_nip'     => '-',
                        'tembusan_list'         => ['Majelis Dikdasmen PCM Tonjong', 'Arsip']
                    ]
                ]
            ],

            // 6. SAMPLE SURAT PERMOHONAN (PMH)
            [
                'match_condition' => ['jenis_surat' => 'pmh', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/PMH/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -6,
                    'jenis_surat'    => 'pmh',
                    'jenis_surat_id' => 1,
                    'bidang_id'      => 1,
                    'tahun'          => 2000,
                    'perihal'        => 'Permohonan Peminjaman Gedung Serbaguna (Master System)',
                    'tujuan'         => 'Kepala Desa Tonjong',
                    'tanggal_surat'  => '2000-01-01',
                    'lampiran'       => '-',
                    'keterangan'     => 'Surat Permohonan Contoh Master System',
                    'pembuat_surat'  => 'System Administrator',
                    'payload_detail' => [
                        'tempat_penerima'       => 'di - Tonjong',
                        'salam_pembuka'         => "Assalamu'alaikum Wr. Wb.",
                        'paragraf_pembuka'      => 'Dalam rangka menyelenggarakan kegiatan Lomba Seni & Olahraga antar sekolah, dengan ini kami mengajukan permohonan izin peminjaman Gedung Serbaguna Desa Tonjong.',
                        'hari_tanggal'          => 'Kamis, 27 Januari 2000',
                        'waktu_acara'           => '08.00 - 16.00 WIB',
                        'tempat_acara'          => 'Gedung Serbaguna Desa Tonjong',
                        'agenda_acara'          => 'Peminjaman Gedung untuk Lomba Seni & Olahraga',
                        'keterangan_acara'      => 'Kami bersedia menjaga kebersihan dan ketertiban lokasi.',
                        'paragraf_penutup'      => 'Demikian permohonan ini kami sampaikan. Atas bantuan dan perkenan Bapak, kami ucapkan terima kasih.',
                        'salam_penutup'         => "Wassalamu'alaikum Wr. Wb.",
                        'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                        'penandatangan_jabatan' => 'Kepala Sekolah',
                        'penandatangan_nip'     => '-',
                        'tembusan_list'         => ['Arsip']
                    ]
                ]
            ],

            // 7. SAMPLE SURAT CUSTOM
            [
                'match_condition' => ['jenis_surat' => 'custom', 'tahun' => 2000],
                'data' => [
                    'nomor_surat'    => '000/CUSTOM/IV.4.AU/F/I/2000',
                    'nomor_urut'     => -7,
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