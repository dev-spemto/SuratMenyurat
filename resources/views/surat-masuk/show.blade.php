@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @php
                $item = $suratMasuk ?? $surat;

                // Tanggal Surat
                $rawTglSurat = $item->tanggal_surat ?? $item->tgl_surat ?? null;
                if ($rawTglSurat instanceof \Carbon\Carbon) {
                    $tglSuratF = $rawTglSurat->translatedFormat('d F Y');
                } elseif ($rawTglSurat) {
                    $tglSuratF = \Carbon\Carbon::parse($rawTglSurat)->translatedFormat('d F Y');
                } else {
                    $tglSuratF = '-';
                }

                // Tanggal Diterima
                $rawTglTerima = $item->tanggal_diterima ?? $item->tanggal_terima ?? $item->tgl_terima ?? null;
                if ($rawTglTerima instanceof \Carbon\Carbon) {
                    $tglTerimaF = $rawTglTerima->translatedFormat('d F Y');
                } elseif ($rawTglTerima) {
                    $tglTerimaF = \Carbon\Carbon::parse($rawTglTerima)->translatedFormat('d F Y');
                } else {
                    $tglTerimaF = '-';
                }

                $filePath = $item->file_surat ?? $item->lampiran ?? null;
            @endphp

            <!-- TOMBOL KEMBALI & AKSI -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('surat-masuk.print', $item->id) }}" target="_blank" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-printer me-1"></i> Cetak Disposisi
                    </a>

                    {{-- Tombol Edit HANYA muncul jika bukan surat default SAMPLE --}}
                    @if (($item->nomor_surat ?? '') !== '000/SMP/SAMPLE/2000')
                        <a href="{{ route('surat-masuk.edit', $item->id) }}" class="btn btn-outline-warning btn-sm fw-semibold">
                            <i class="bi bi-pencil me-1"></i> Edit Surat
                        </a>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- BANNER INFORMASI UTAMA -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Surat Asal</span>
                        <h3 class="fw-bold m-0 text-white">{{ $item->nomor_surat }}</h3>
                        <div class="mt-2 text-white-50 small">
                            Pengirim / Instansi: <strong class="text-white">{{ $item->pengirim ?? $item->asal_surat ?? '-' }}</strong>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-white text-primary border border-white px-3 py-2 fs-6 fw-semibold">
                            SURAT MASUK
                        </span>
                    </div>
                </div>
            </div>

            <!-- DETAIL INFORMATION CARD -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Rincian Informasi Surat Masuk</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Nomor Surat Asal</span>
                            <span class="fw-bold text-success">{{ $item->nomor_surat }}</span>
                        </div>

                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Jenis Surat</span>
                            <span class="fw-bold text-dark">
                                @if ($item->jenisSurat)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                        {{ $item->jenisSurat->kode }} - {{ $item->jenisSurat->nama }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
                        </div>

                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Pengirim / Instansi</span>
                            <span class="fw-bold text-dark">{{ $item->pengirim ?? $item->asal_surat ?? '-' }}</span>
                        </div>

                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Penerima Surat (Petugas TU)</span>
                            <span class="fw-bold text-dark">{{ $item->penerima_surat ?? $item->penerima ?? '-' }}</span>
                        </div>

                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Tanggal Terbit Surat</span>
                            <span class="fw-bold text-dark"><i class="bi bi-calendar-event me-1 text-muted"></i>{{ $tglSuratF }}</span>
                        </div>

                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Tanggal Diterima Sekolah</span>
                            <span class="fw-bold text-dark text-success"><i class="bi bi-box-arrow-in-down me-1"></i>{{ $tglTerimaF }}</span>
                        </div>

                        <div class="col-12 border-bottom pb-2">
                            <span class="text-muted small d-block">Perihal Surat</span>
                            <span class="fw-bold text-dark fs-6">{{ $item->perihal }}</span>
                        </div>

                        <div class="col-12 border-bottom pb-2">
                            <span class="text-muted small d-block">Keterangan / Disposisi</span>
                            <span class="fw-bold text-dark">{{ $item->keterangan ?: 'Tidak ada catatan disposisi tambahan.' }}</span>
                        </div>

                        <div class="col-12 pt-2">
                            <span class="text-muted small d-block mb-2">File Berkas Scan Digital</span>
                            @if ($filePath)
                                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-pdf fs-3 text-danger"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ basename($filePath) }}</div>
                                            <span class="text-muted style-italic small" style="font-size: 11px;">Berkas Terlampir Resmi</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-success btn-sm fw-bold">
                                        <i class="bi bi-eye-fill me-1"></i> Buka / Unduh File
                                    </a>
                                </div>
                            @else
                                <span class="text-muted small"><em>Tidak ada berkas scan yang diunggah.</em></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER AKSI HAPUS -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Surat Masuk
                </a>

                {{-- Form Hapus HANYA muncul jika bukan surat default SAMPLE --}}
                @if (($item->nomor_surat ?? '') !== '000/SMP/SAMPLE/2000')
                    <form action="{{ route('surat-masuk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data surat masuk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                            <i class="bi bi-trash me-1"></i> Hapus Surat Masuk
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection