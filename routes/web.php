<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormatSuratController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

// ================================
// DASHBOARD
// ================================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ================================
// BACKUP & RESTORE DATABASE
// ================================
Route::get('/backup', [BackupController::class, 'create'])->name('backup.create');
Route::get('/restore', [BackupController::class, 'restoreForm'])->name('backup.restore.form');
Route::post('/restore', [BackupController::class, 'restore'])->name('backup.restore');

// ================================
// BUKU AGENDA / ARSIP SURAT KELUAR MURNI
// ================================
Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
Route::get('/surat-keluar/export-pdf', [SuratKeluarController::class, 'exportPdf'])->name('surat-keluar.export-pdf');
Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');

Route::get('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
Route::get('/surat-keluar/{suratKeluar}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
Route::put('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
Route::delete('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
Route::get('/surat-keluar/{suratKeluar}/print', [SuratKeluarController::class, 'print'])->name('surat-keluar.print');
Route::patch('/surat-keluar/{suratKeluar}/batalkan', [SuratKeluarController::class, 'batalkan'])->name('surat-keluar.batalkan');

// ================================
// SURAT MASUK
// ================================
Route::get('/surat-masuk/export-pdf', [SuratMasukController::class, 'exportPdf'])->name('surat-masuk.export-pdf');
Route::get('/surat-masuk/{suratMasuk}/print', [SuratMasukController::class, 'print'])->name('surat-masuk.print');
Route::resource('surat-masuk', SuratMasukController::class);

// ================================
// MASTER DATA PEGAWAI / GURU
// ================================
Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
Route::resource('pegawai', PegawaiController::class);

// ================================
// MASTER DATA SISWA & API SEARCH
// ================================
Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::get('/api/siswa/search', [SiswaController::class, 'getSiswaJson'])->name('api.siswa.search');
Route::resource('siswa', SiswaController::class);

// ================================
// DOWNLOAD FORMAT SURAT
// ================================
Route::resource('format-surat', FormatSuratController::class);

// ================================
// MASTER DATA JENIS SURAT & BIDANG
// ================================
if (class_exists(JenisSuratController::class)) {
    Route::resource('jenis-surat', JenisSuratController::class)->except(['show']);
}
if (class_exists(BidangController::class)) {
    Route::resource('bidang', BidangController::class)->except(['show']);
}

// ================================
// GENERATOR SURAT KHUSUS (SPPD, KET. AKTIF, CUSTOM)
// ================================
Route::get('/surat/{surat}/print', [SuratController::class, 'print'])->name('surat.print');
Route::resource('surat', SuratController::class);

// ================================
// PENGATURAN SYSTEM & PROFIL SEKOLAH
// ================================
Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

Route::view('/test-layout', 'test-layout');