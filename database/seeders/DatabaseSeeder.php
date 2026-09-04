<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan Seeder Utama
        $this->call([
            JenisSuratSeeder::class,
            BidangSeeder::class,
            PegawaiSeeder::class,
            SiswaSeeder::class,
            SuratSeeder::class, // Mengisi generator dokumen sampel
        ]);

        // 2. Akun Administrator Default
        User::firstOrCreate(
            ['email' => 'admin@smpmuhtonjong.sch.id'],
            [
                'name' => 'Administrator TU',
                'password' => Hash::make('password'),
            ]
        );

        // Akun Testing
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $timestamp = date('Y-m-d H:i:s');

        // 3. Konfigurasi Identitas Sekolah & Kop Surat
        DB::table('pengaturans')->updateOrInsert(
            ['id' => 1],
            [
                'nama_majelis'        => 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL',
                'pimpinan'            => 'PIMPINAN DAERAH MUHAMMADIYAH BREBES',
                'nama_sekolah'        => 'SMP MUHAMMADIYAH TONJONG',
                'akreditasi'          => 'TERAKREDITASI A',
                'npsn'                => '20326564',
                'nss'                 => '202032906045',
                'alamat'              => 'Jl. Raya Linggapura No. 46, RT 03/RW 03 - Tonjong - Brebes',
                'kode_pos'            => '52271',
                'kota'                => 'Brebes',
                'nomor_hp'            => '(+62) 851-850-333-77',
                'email'               => 'smpmuhitonjong@gmail.com',
                'website'             => 'https://smpmuhtonjong.sch.id',
                'nama_kepala_sekolah' => 'IRFAN TUNZILA, S. Ag.',
                'nip_kepala_sekolah'  => '-',
                'created_at'          => $timestamp,
                'updated_at'          => $timestamp,
            ]
        );

        $jenisPedoman = JenisSurat::where('kode', 'PED')->first() ?? JenisSurat::first();
        $bidang = Bidang::first();

        if ($jenisPedoman && $bidang) {
            // 4. Contoh Default Surat Keluar Murni (Master System) - TANPA 'tgl_surat'
            SuratKeluar::firstOrCreate(
                [
                    'nomor_urut' => 0,
                    'tahun'      => 2000,
                ],
                [
                    'jenis_surat_id' => $jenisPedoman->id,
                    'jenis_surat'    => 'biasa',
                    'bidang_id'      => $bidang->id,
                    'nomor_surat'    => sprintf('000/%s/IV.4.AU/%s/I/2000', $jenisPedoman->kode, $bidang->kode),
                    'tanggal_surat'  => '2000-01-01',
                    'perihal'        => 'Contoh Default Surat Keluar (Master System)',
                    'tujuan'         => 'Internal Sekolah',
                    'lampiran'       => '-',
                    'payload_detail' => ['keterangan' => 'Sample Master System'],
                    'pembuat_surat'  => 'System Administrator',
                    'keterangan'     => 'Surat sistem sebagai contoh/default.',
                    'status'         => 'aktif',
                ]
            );

            // 5. Contoh Default Surat Masuk (Master System)
            SuratMasuk::firstOrCreate(
                [
                    'nomor_surat' => '000/SMP/SAMPLE/2000',
                ],
                [
                    'jenis_surat_id' => $jenisPedoman->id,
                    'tanggal_surat'  => '2000-01-01',
                    'tanggal_terima' => '2000-01-01',
                    'pengirim'       => 'Pimpinan Daerah Muhammadiyah Brebes',
                    'penerima_surat' => 'Petugas Tata Usaha',
                    'perihal'        => 'Contoh Default Surat Masuk (Master System)',
                    'keterangan'     => 'Surat sistem sebagai contoh/default.',
                    'file_surat'     => 'surat-masuk/sample-lampiran.pdf',
                ]
            );
        }
    }
}