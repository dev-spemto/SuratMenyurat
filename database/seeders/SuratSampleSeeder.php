<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surat;
use App\Models\SuratMasuk;

class SuratSampleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SAMPLE SURAT MASUK
        if (class_exists(SuratMasuk::class)) {
            SuratMasuk::create([
                'nomor_surat_asal' => '000/SMP/SAMPLE/2000',
                'pengirim'         => 'Pimpinan Daerah Muhammadiyah Brebes',
                'perihal'          => 'Contoh Default Surat Masuk (Master System)',
                'tanggal_surat'    => '2000-01-01',
                'tgl_diterima'     => '2000-01-01',
                'scan_file'        => 'sample.pdf',
            ]);
        }

        // 2. SAMPLE SURAT KELUAR (UMUM)
        Surat::create([
            'nomor_urut'     => 0, // Master Sample Utama (nomor_urut=0, tahun=2000)
            'nomor_surat'    => '000/PED/IV.4.AU/A/I/2000',
            'jenis_surat'    => 'biasa',
            'jenis_surat_id' => 3,
            'bidang_id'      => 1,
            'tahun'          => 2000,
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
        ]);

        // 3. SAMPLE SPPD (Gunakan nomor_urut -1 agar tidak melanggar UNIQUE constraint)
        Surat::create([
            'nomor_urut'     => -1,
            'nomor_surat'    => '000/SMP/SAMPLE/2000',
            'jenis_surat'    => 'sppd',
            'jenis_surat_id' => 1,
            'bidang_id'      => 1,
            'tahun'          => 2000,
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
        ]);

        // 4. SAMPLE AKTIF MENGAJAR (Gunakan nomor_urut -2)
        Surat::create([
            'nomor_urut'     => -2,
            'nomor_surat'    => '000/SMP/SAMPLE/2000',
            'jenis_surat'    => 'aktif_mengajar',
            'jenis_surat_id' => 1,
            'bidang_id'      => 1,
            'tahun'          => 2000,
            'perihal'        => 'Surat Keterangan Aktif Mengajar (Master System)',
            'tujuan'         => 'Siti Aminah, S.Pd.',
            'tanggal_surat'  => '2000-01-01',
            'lampiran'       => '-',
            'payload_detail' => [
                'nama_guru'             => 'Siti Aminah, S.Pd.',
                'ttl'                   => 'Brebes, 01 Januari 1985',
                'nuptk'                 => '1234567890123456',
                'nrg'                   => '-',
                'nbm'                   => '100200',
                'nik'                   => '3329000000000001',
                'jenis_kelamin'         => 'Perempuan',
                'pendidikan_terakhir'   => 'S1 Pendidikan Bahasa Indonesia',
                'tmt'                   => '2010-07-15',
                'alamat'                => 'Tonjong, Brebes',
                'satminkal'             => 'SMP Muhammadiyah Tonjong (Induk)',
                'jabatan_status'        => 'Guru Tetap Yayasan',
                'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                'penandatangan_jabatan' => 'Kepala Sekolah',
            ],
        ]);

        // 5. SAMPLE AKTIF BELAJAR (Gunakan nomor_urut -3)
        Surat::create([
            'nomor_urut'     => -3,
            'nomor_surat'    => '000/SMP/SAMPLE/2000',
            'jenis_surat'    => 'aktif_belajar',
            'jenis_surat_id' => 1,
            'bidang_id'      => 1,
            'tahun'          => 2000,
            'perihal'        => 'Surat Keterangan Aktif Belajar (Master System)',
            'tujuan'         => 'Muhammad Rizky Pratama',
            'tanggal_surat'  => '2000-01-01',
            'lampiran'       => '-',
            'payload_detail' => [
                'nama_siswa'            => 'Muhammad Rizky Pratama',
                'nis'                   => '2021001',
                'nisn'                  => '0051234567',
                'nik'                   => '3329000000000002',
                'ttl'                   => 'Brebes, 10 Mei 2010',
                'jenis_kelamin'         => 'Laki-Laki',
                'nama_orang_tua'        => 'Budi Santoso',
                'nama_ayah'             => 'Budi Santoso',
                'nama_ibu'              => 'Siti Rahmawati',
                'kelas'                 => 'IX A',
                'alamat'                => 'Tonjong, Brebes',
                'keperluan'             => 'Persyaratan Pengajuan Beasiswa / Contoh Default System',
                'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                'penandatangan_jabatan' => 'Kepala Sekolah',
            ],
        ]);

        // 6. SAMPLE SURAT CUSTOM (Gunakan nomor_urut -4)
        Surat::create([
            'nomor_urut'     => -4,
            'nomor_surat'    => '000/SMP/SAMPLE/2000',
            'jenis_surat'    => 'custom',
            'jenis_surat_id' => 1,
            'bidang_id'      => 1,
            'tahun'          => 2000,
            'perihal'        => 'Surat Pemberitahuan Custom (Master System)',
            'tujuan'         => 'Pimpinan Daerah Muhammadiyah Brebes',
            'tanggal_surat'  => '2000-01-01',
            'lampiran'       => '1 Lembar',
            'payload_detail' => [
                'isi_surat'             => 'Ini adalah contoh dokumen template custom yang dapat digunakan sebagai acuan awal format penyusunan surat resmi sekolah.',
                'waktu_acara'           => 'Sabtu, 01 Januari 2000 / 08.00 WIB - Selesai',
                'tempat_acara'          => 'Aula SMP Muhammadiyah Tonjong',
                'penandatangan_nama'    => 'IRFAN TUNZILA, S. Ag.',
                'penandatangan_jabatan' => 'Kepala Sekolah',
            ],
        ]);
    }
}