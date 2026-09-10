<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\Surat;
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

        // Statistik Surat Keluar & Masuk
        $jumlahSurat = SuratKeluar::where('tahun', $tahun)->count();
        $suratTerakhir = SuratKeluar::where('tahun', $tahun)->latest('nomor_urut')->first();
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

        // Inisialisasi Statistik Pembuatan Dokumen
        $totalSppd          = 0;
        $totalAktifMengajar = 0;
        $totalAktifBelajar  = 0;
        $totalUndangan      = 0;
        $totalPemberitahuan = 0;
        $totalPermohonan    = 0;
        $totalCustom        = 0;

        // Cek nama tabel generator surat di database Anda
        $tableName = null;
        if (Schema::hasTable('surats')) {
            $tableName = 'surats';
        } elseif (Schema::hasTable('surat_menyurats')) {
            $tableName = 'surat_menyurats';
        } elseif (Schema::hasTable('surat_keluars')) {
            $tableName = 'surat_keluars';
        }

        if ($tableName) {
            $totalSppd          = DB::table($tableName)->whereIn('jenis_surat', ['sppd', 'surat_tugas'])->count();
            $totalAktifMengajar = DB::table($tableName)->where('jenis_surat', 'aktif_mengajar')->count();
            $totalAktifBelajar  = DB::table($tableName)->where('jenis_surat', 'aktif_belajar')->count();
            $totalUndangan      = DB::table($tableName)->where('jenis_surat', 'und')->count();
            $totalPemberitahuan = DB::table($tableName)->where('jenis_surat', 'pbh')->count();
            $totalPermohonan    = DB::table($tableName)->where('jenis_surat', 'pmh')->count();
            $totalCustom        = DB::table($tableName)->where('jenis_surat', 'custom')->count();
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
            'totalUndangan',
            'totalPemberitahuan',
            'totalPermohonan',
            'totalCustom'
        ));
    }
}