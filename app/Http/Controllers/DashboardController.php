<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = date('Y');

        // Format Hari dan Tanggal Bahasa Indonesia
        Carbon::setLocale('id');
        $hariTanggal = Carbon::now()->isoFormat('dddd, D MMMM YYYY');

        // Data Pengaturan Kop / Kontak Sekolah
        $pengaturan = Pengaturan::first();

        // Statistik Surat Keluar
        $jumlahSurat = SuratKeluar::where('tahun', $tahun)->count();
        $suratTerakhir = SuratKeluar::where('tahun', $tahun)->latest('nomor_urut')->first();

        // Statistik Surat Masuk
        $jumlahSuratMasuk = SuratMasuk::whereYear('tanggal_terima', $tahun)->count();

        // Data Surat Terbaru untuk Tabel Dashboard
        $suratTerbaru = SuratKeluar::with(['jenisSurat', 'bidang'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $suratMasukTerbaru = SuratMasuk::with('jenisSurat')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Hitung Statistik Pembuatan Dokumen (Pengecekan Aman)
        $totalSppd = 0;
        $totalAktifMengajar = 0;
        $totalAktifBelajar = 0;
        $totalCustom = 0;

        // Cek nama tabel generator surat di database Anda
        $tableName = null;
        if (Schema::hasTable('surat_menyurats')) {
            $tableName = 'surat_menyurats';
        } elseif (Schema::hasTable('surats')) {
            $tableName = 'surats';
        }

        if ($tableName) {
            $totalSppd = DB::table($tableName)->whereIn('jenis_surat', ['sppd', 'surat_tugas'])->count();
            $totalAktifMengajar = DB::table($tableName)->where('jenis_surat', 'aktif_mengajar')->count();
            $totalAktifBelajar = DB::table($tableName)->where('jenis_surat', 'aktif_belajar')->count();
            $totalCustom = DB::table($tableName)->whereNotIn('jenis_surat', ['sppd', 'surat_tugas', 'aktif_mengajar', 'aktif_belajar'])->count();
        }

        return view('dashboard', compact(
            'tahun',
            'hariTanggal',
            'pengaturan',
            'jumlahSurat',
            'suratTerakhir',
            'jumlahSuratMasuk',
            'suratTerbaru',
            'suratMasukTerbaru',
            'totalSppd',
            'totalAktifMengajar',
            'totalAktifBelajar',
            'totalCustom'
        ));
    }
}