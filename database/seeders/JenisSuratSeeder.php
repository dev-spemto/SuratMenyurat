<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = date('Y-m-d H:i:s');

        // Master Kode Resmi Standar Dikdasmen
        $jenisSurats = [
            ['kode' => 'QDH', 'nama' => 'Qaidah', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'PRN', 'nama' => 'Peraturan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'PED', 'nama' => 'Pedoman', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'KTN', 'nama' => 'Ketentuan Majelis', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'KEP', 'nama' => 'Keputusan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'INS', 'nama' => 'Instruksi', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'MLM', 'nama' => 'Maklumat', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'EDR', 'nama' => 'Edaran', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'PER', 'nama' => 'Pernyataan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'KET', 'nama' => 'Keterangan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'REK', 'nama' => 'Rekomendasi', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'SRN', 'nama' => 'Seruan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'HIM', 'nama' => 'Himbauan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'KSA', 'nama' => 'Surat Kuasa', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'TGS', 'nama' => 'Surat Tugas', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'PPD', 'nama' => 'Perjalanan Dinas', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'PKS', 'nama' => 'Perjanjian Kerja Sama', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('jenis_surats')->insertOrIgnore($jenisSurats);
    }
}