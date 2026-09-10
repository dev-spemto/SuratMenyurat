<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surat;
use App\Models\SuratMasuk;

class SuratSampleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SAMPLE SURAT MASUK (MASTER SYSTEM)
        if (class_exists(SuratMasuk::class)) {
            SuratMasuk::updateOrCreate(
                ['nomor_surat' => '000/SMP/SAMPLE/2000'],
                [
                    'nomor_urut'     => 0,
                    'tahun'          => 2000,
                    'jenis_surat_id' => null,
                    'pengirim'       => 'Pimpinan Daerah Muhammadiyah Brebes',
                    'perihal'        => 'Contoh Default Surat Masuk (Master System)',
                    'tanggal_surat'  => '2000-01-01',
                    'tanggal_terima' => '2000-01-01',
                    'penerima_surat' => 'Petugas TU (System Administrator)',
                    'file_surat'     => null,
                    'keterangan'     => 'Data contoh/sample bawaan sistem, tidak dapat diedit atau dihapus.',
                ]
            );
        }

        // 2. SAMPLE SURAT KELUAR (UMUM / BIASA)
        Surat::updateOrCreate(
            ['jenis_surat' => 'biasa', 'tahun' => 2000],
            [
                'nomor_urut'     => 0,
                'nomor_surat'    => '000/PED/IV.4.AU/A/I/2000',
                'jenis_surat_id' => 3,
                'bidang_id'      => 1,
                'perihal'        => 'Contoh Default Surat Keluar (Master System)',
                'tujuan'         => 'Internal Sekolah',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'keterangan'            => 'Sample Master System',
                    'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                ],
                'pembuat_surat'  => 'System Administrator',
                'keterangan'     => 'Surat sistem sebagai contoh/default.',
                'status'         => 'aktif',
            ]
        );

        // 3. SAMPLE SPPD (nomor_urut: -1)
        Surat::updateOrCreate(
            ['jenis_surat' => 'sppd', 'tahun' => 2000],
            [
                'nomor_urut'     => -1,
                'nomor_surat'    => '000/PPD/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Pendampingan Siswa Lomba Olimpiade Sains',
                'tujuan'         => 'Drs. H. Ahmad Dahlan, M.Pd.',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'maksud_dinas'          => 'Pendampingan Siswa Lomba Olimpiade Sains Tingkat Kabupaten',
                    'tempat_tujuan'         => 'Dinas Pendidikan Kabupaten Brebes',
                    'tgl_berangkat'         => '2000-01-01',
                    'tgl_kembali'           => '2000-01-01',
                    'transportasi'          => 'Kendaraan Dinas / Pribadi',
                    'keterangan'            => 'Contoh Default Master System',
                    'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                    'pegawai_list'          => [
                        [
                            'id'         => 1,
                            'nama'       => 'Drs. H. Ahmad Dahlan, M.Pd.',
                            'jabatan'    => 'Guru / Pendamping',
                            'nip'        => '197001012000011001',
                            'unit_kerja' => 'SMP MUHAMMADIYAH TONJONG',
                        ]
                    ]
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 4. SAMPLE AKTIF MENGAJAR (nomor_urut: -2)
        Surat::updateOrCreate(
            ['jenis_surat' => 'aktif_mengajar', 'tahun' => 2000],
            [
                'nomor_urut'     => -2,
                'nomor_surat'    => '000/KET-MENGAJAR/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Keterangan Aktif Mengajar (Master System)',
                'tujuan'         => 'Siti Aminah, S.Pd.',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'nama_guru'           => 'Siti Aminah, S.Pd.',
                    'ttl'                 => 'Brebes, 01 Januari 1985',
                    'nuptk'               => '1234567890123456',
                    'nrg'                 => '-',
                    'nbm'                 => '100200',
                    'nik'                 => '3329000000000001',
                    'jenis_kelamin'       => 'Perempuan',
                    'pendidikan_terakhir' => 'S1 Pendidikan Bahasa Indonesia',
                    'tmt'                 => '2010-07-15',
                    'alamat'              => 'Tonjong, Brebes',
                    'satminkal'           => 'SMP Muhammadiyah Tonjong (Induk)',
                    'jabatan_status'      => 'Guru Tetap Yayasan',
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 5. SAMPLE AKTIF BELAJAR (nomor_urut: -3)
        Surat::updateOrCreate(
            ['jenis_surat' => 'aktif_belajar', 'tahun' => 2000],
            [
                'nomor_urut'     => -3,
                'nomor_surat'    => '000/KET-BELAJAR/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Keterangan Aktif Belajar (Master System)',
                'tujuan'         => 'Muhammad Rizky Pratama',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'nama_siswa'          => 'Muhammad Rizky Pratama',
                    'nis'                 => '2021001',
                    'nisn'                => '0051234567',
                    'nik'                 => '3329000000000002',
                    'ttl'                 => 'Brebes, 10 Mei 2010',
                    'jenis_kelamin'       => 'Laki-Laki',
                    'nama_orang_tua'      => 'Budi Santoso',
                    'nama_ayah'           => 'Budi Santoso',
                    'nama_ibu'            => 'Siti Rahmawati',
                    'kelas'               => 'IX A',
                    'alamat'              => 'Tonjong, Brebes',
                    'keperluan'           => 'Persyaratan Pengajuan Beasiswa / Contoh Default System',
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 6. SAMPLE SURAT UNDANGAN / UND (nomor_urut: -4)
        Surat::updateOrCreate(
            ['jenis_surat' => 'und', 'tahun' => 2000],
            [
                'nomor_urut'     => -4,
                'nomor_surat'    => '000/UND/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Undangan Rapat Evaluasi (Master System)',
                'tujuan'         => 'Bapak/Ibu Guru dan Karyawan',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'tempat_penerima'     => 'di - Tempat',
                    'salam_pembuka'       => "Assalamu'alaikum Wr. Wb.",
                    'paragraf_pembuka'    => "Ba'da salam, teriring doa semoga Bapak/Ibu senantiasa dalam keadaan sehat wal'afiat serta sukses dalam menjalankan aktivitas sehari-hari.",
                    'hari_tanggal'        => 'Sabtu, 15 Januari 2000',
                    'waktu_acara'         => '08.00 WIB - Selesai',
                    'tempat_acara'        => 'Ruang Rapat SMP Muhammadiyah Tonjong',
                    'agenda_acara'        => 'Rapat Evaluasi Pembelajaran & Awal Semester',
                    'keterangan_acara'    => 'Mengingat pentingnya acara, dimohon hadir tepat waktu.',
                    'paragraf_penutup'    => 'Demikian surat undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.',
                    'salam_penutup'       => "Wassalamu'alaikum Wr. Wb.",
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                    'penandatangan_nip'   => '-',
                    'tembusan_list'       => ['Arsip Sekolah']
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 7. SAMPLE SURAT PEMBERITAHUAN / PBH (nomor_urut: -5)
        Surat::updateOrCreate(
            ['jenis_surat' => 'pbh', 'tahun' => 2000],
            [
                'nomor_urut'     => -5,
                'nomor_surat'    => '000/PBH/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Pemberitahuan Ujian (Master System)',
                'tujuan'         => 'Bapak/Ibu Orang Tua / Wali Murid',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '1 Lembar',
                'payload_detail' => [
                    'tempat_penerima'     => 'di - Tempat',
                    'salam_pembuka'       => "Assalamu'alaikum Wr. Wb.",
                    'paragraf_pembuka'    => 'Diberitahukan dengan hormat kepada Bapak/Ibu Orang Tua/Wali Murid bahwa pelaksanaan Ujian Tengah Semester akan dilaksanakan sesuai jadwal terlampir.',
                    'hari_tanggal'        => 'Senin - Sabtu, 20-25 Januari 2000',
                    'waktu_acara'         => '07.30 WIB - Selesai',
                    'tempat_acara'        => 'Gedung SMP Muhammadiyah Tonjong',
                    'agenda_acara'        => 'Pelaksanaan Ujian Tengah Semester Genap',
                    'keterangan_acara'    => 'Mohon membimbing putra/putrinya dalam belajar di rumah.',
                    'paragraf_penutup'    => 'Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.',
                    'salam_penutup'       => "Wassalamu'alaikum Wr. Wb.",
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                    'penandatangan_nip'   => '-',
                    'tembusan_list'       => ['Arsip Sekolah']
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 8. SAMPLE SURAT PERMOHONAN / PMH (nomor_urut: -6)
        Surat::updateOrCreate(
            ['jenis_surat' => 'pmh', 'tahun' => 2000],
            [
                'nomor_urut'     => -6,
                'nomor_surat'    => '000/PMH/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Permohonan Izin Tempat (Master System)',
                'tujuan'         => 'Kepala Desa Tonjong',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '-',
                'payload_detail' => [
                    'tempat_penerima'     => 'di - Tonjong',
                    'salam_pembuka'       => "Assalamu'alaikum Wr. Wb.",
                    'paragraf_pembuka'    => 'Dalam rangka menyelenggarakan kegiatan Lomba Seni & Olahraga, dengan ini kami mengajukan permohonan izin peminjaman Gedung Serbaguna Desa Tonjong.',
                    'hari_tanggal'        => 'Kamis, 27 Januari 2000',
                    'waktu_acara'         => '08.00 - 16.00 WIB',
                    'tempat_acara'        => 'Gedung Serbaguna Desa Tonjong',
                    'agenda_acara'        => 'Peminjaman Gedung untuk Lomba Seni & Olahraga',
                    'keterangan_acara'    => 'Kami bersedia menjaga kebersihan dan ketertiban lokasi.',
                    'paragraf_penutup'    => 'Demikian permohonan ini kami sampaikan. Atas bantuan dan perkenan Bapak, kami ucapkan terima kasih.',
                    'salam_penutup'       => "Wassalamu'alaikum Wr. Wb.",
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                    'penandatangan_nip'   => '-',
                    'tembusan_list'       => ['Arsip']
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );

        // 9. SAMPLE SURAT CUSTOM (nomor_urut: -7)
        Surat::updateOrCreate(
            ['jenis_surat' => 'custom', 'tahun' => 2000],
            [
                'nomor_urut'     => -7,
                'nomor_surat'    => '000/CUSTOM/SAMPLE/2000',
                'jenis_surat_id' => 1,
                'bidang_id'      => 1,
                'perihal'        => 'Surat Pemberitahuan Custom (Master System)',
                'tujuan'         => 'Pimpinan Daerah Muhammadiyah Brebes',
                'tanggal_surat'  => '2000-01-01',
                'lampiran'       => '1 Lembar',
                'payload_detail' => [
                    'isi_surat'           => 'Ini adalah contoh dokumen template custom yang dapat digunakan sebagai acuan awal format penyusunan surat resmi sekolah.',
                    'waktu_acara'         => 'Sabtu, 01 Januari 2000 / 08.00 WIB - Selesai',
                    'tempat_acara'        => 'Aula SMP Muhammadiyah Tonjong',
                    'penandatangan_nama'  => 'IRFAN TUNZILA, S. Ag.',
                    'penandatangan_jabatan' => 'Kepala Sekolah',
                ],
                'pembuat_surat'  => 'System Administrator',
            ]
        );
    }
}