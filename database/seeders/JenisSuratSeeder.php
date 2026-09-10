<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = date('Y-m-d H:i:s');

        // Master Kode Resmi - Uppercase untuk Kode Klasifikasi Surat
        $jenisSurats = [
            ['kode' => 'EDR', 'nama' => 'Edaran', 'aktif' => 1],
            ['kode' => 'HIM', 'nama' => 'Himbauan', 'aktif' => 1],
            ['kode' => 'INS', 'nama' => 'Instruksi', 'aktif' => 1],
            ['kode' => 'KEP', 'nama' => 'Keputusan', 'aktif' => 1],
            ['kode' => 'KTN', 'nama' => 'Ketentuan Majelis', 'aktif' => 1],
            ['kode' => 'KET', 'nama' => 'Keterangan', 'aktif' => 1],
            ['kode' => 'MLM', 'nama' => 'Maklumat', 'aktif' => 1],
            ['kode' => 'MOU', 'nama' => 'MOU', 'aktif' => 1],
            ['kode' => 'PBH', 'nama' => 'Pemberitahuan', 'aktif' => 1],
            ['kode' => 'PED', 'nama' => 'Pedoman', 'aktif' => 1],
            ['kode' => 'PKS', 'nama' => 'Perjanjian Kerja Sama', 'aktif' => 1],
            ['kode' => 'PPD', 'nama' => 'Perjalanan Dinas', 'aktif' => 1],
            ['kode' => 'PRN', 'nama' => 'Peraturan', 'aktif' => 1],
            ['kode' => 'PMH', 'nama' => 'Permohonan', 'aktif' => 1],
            ['kode' => 'PER', 'nama' => 'Pernyataan', 'aktif' => 1],
            ['kode' => 'QDH', 'nama' => 'Qaidah', 'aktif' => 1],
            ['kode' => 'REK', 'nama' => 'Rekomendasi', 'aktif' => 1],
            ['kode' => 'SERT', 'nama' => 'Sertifikat', 'aktif' => 1],
            ['kode' => 'SRN', 'nama' => 'Seruan', 'aktif' => 1],
            ['kode' => 'KSA', 'nama' => 'Surat Kuasa', 'aktif' => 1],
            ['kode' => 'TGS', 'nama' => 'Surat Tugas', 'aktif' => 1],
            ['kode' => 'UND', 'nama' => 'Undangan', 'aktif' => 1],
            ['kode' => 'SPPD', 'nama' => 'Surat Perintah Perjalanan Dinas (SPPD)', 'aktif' => 1],
            ['kode' => 'AKTIF_BELAJAR', 'nama' => 'Surat Keterangan Aktif Belajar', 'aktif' => 1],
            ['kode' => 'AKTIF_MENGAJAR', 'nama' => 'Surat Keterangan Aktif Mengajar', 'aktif' => 1],
            ['kode' => 'CUSTOM', 'nama' => 'Surat Lainnya / Custom', 'aktif' => 1],
        ];

        foreach ($jenisSurats as $item) {
            DB::table('jenis_surats')->updateOrInsert(
                ['kode' => $item['kode']],
                [
                    'nama'       => $item['nama'],
                    'aktif'      => $item['aktif'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]
            );
        }
    }
}