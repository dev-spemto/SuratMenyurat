<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = date('Y-m-d H:i:s');

        $bidangs = [
            ['kode' => 'A', 'nama' => 'Umum dan Tata Usaha', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'B', 'nama' => 'Organisasi', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'C', 'nama' => 'Keuangan, Perlengkapan/Perbekalan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'D', 'nama' => 'Personalia', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'E', 'nama' => 'Keagamaan, Dakwah/Tabligh, dan Penyiaran', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'F', 'nama' => 'Pendidikan, Penelitian, dan Latihan (Darul Arqam, Baitul Arqam, Latihan Instruktur, dan sebagainya)', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'G', 'nama' => 'Perekonomian', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'H', 'nama' => 'Kesehatan, Sosial dan Kemasyarakatan', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'I', 'nama' => 'Hukum, Perundang-Undangan, Hak Asasi Manusia', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'J', 'nama' => 'Hubungan Luar/Masyarakat', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'K', 'nama' => 'Wakaf, Zakat, Infak, dan Sedekah', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'L', 'nama' => 'Pemberdayaan Masyarakat', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'M', 'nama' => 'Kepustakaan, Informasi, dan Digitalisasi', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'N', 'nama' => 'Seni Budaya dan Olahraga', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['kode' => 'O', 'nama' => 'Lain-lain', 'aktif' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('bidangs')->insertOrIgnore($bidangs);
    }
}