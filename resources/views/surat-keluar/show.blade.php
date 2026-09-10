@extends('layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @php
                $item = $suratKeluar ?? $surat;
                
                // Normalisasi variabel payload agar selalu bertipe Array
                $p = $item->payload_detail ?? $item->payload ?? [];
                if (is_string($p)) {
                    $p = json_decode($p, true) ?? [];
                }

                $jenis = $item->jenis_surat ?? strtolower($item->jenisSurat->kode ?? 'custom');
                $rawDate = $item->tanggal_surat ?? $item->tgl_surat ?? null;
                if ($rawDate instanceof \Carbon\Carbon) {
                    $tglFormatted = $rawDate->translatedFormat('d F Y');
                } elseif ($rawDate) {
                    $tglFormatted = \Carbon\Carbon::parse($rawDate)->translatedFormat('d F Y');
                } else {
                    $tglFormatted = '-';
                }

                // Proteksi khusus Master Default System
                $isMasterDefault = $item->is_master_sample 
                    || ($item->tahun == 2000 && ($item->nomor_urut ?? 0) <= 0) 
                    || $item->nomor_surat === '000/SMP/SAMPLE/2000';
            @endphp

            <!-- TOMBOL KEMBALI & NOTIFIKASI -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Surat
                </a>
                <div class="d-flex gap-2">
                    @if (!$isMasterDefault && ($item->status ?? 'aktif') === 'aktif')
                        <a href="{{ route('surat-keluar.edit', $item->id) }}" class="btn btn-outline-warning btn-sm fw-semibold">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('surat-keluar.print', $item->id) }}" target="_blank" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-printer me-1"></i> Cetak Dokumen
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- BANNER NOMOR SURAT -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-success text-white">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Surat Keluar</span>
                        <h3 class="fw-bold m-0 text-white">{{ $item->nomor_surat }}</h3>
                        <div class="mt-2 text-white-50 small">
                            Nomor Urut: <strong>{{ sprintf('%03d', $item->nomor_urut ?? 0) }}</strong> &bull; Tahun: <strong>{{ $item->tahun ?? date('Y') }}</strong>
                        </div>
                    </div>
                    <div>
                        @if (($item->status ?? 'aktif') === 'aktif')
                            <span class="badge bg-white text-success border border-white px-3 py-2 fs-6 fw-semibold">STATUS: AKTIF</span>
                        @else
                            <span class="badge bg-danger text-white border border-white px-3 py-2 fs-6 fw-semibold">STATUS: DIBATALKAN</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- DETAIL INFORMASI SURAT -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Utama Surat</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Tanggal Surat</span>
                            <span class="fw-bold text-dark">{{ $tglFormatted }}</span>
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Jenis Surat</span>
                            <span class="fw-bold text-dark">
                                {{ $item->jenisSurat ? $item->jenisSurat->nama : ($item->jenis_surat ?? '-') }}
                                @if($item->jenisSurat?->kode)
                                    <span class="badge bg-light text-primary border ms-1">{{ $item->jenisSurat->kode }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Bidang / Sub-Bagian</span>
                            <span class="fw-bold text-dark">
                                {{ $item->bidang ? $item->bidang->nama : '-' }}
                                @if($item->bidang?->kode)
                                    <span class="badge bg-light text-secondary border ms-1">{{ $item->bidang->kode }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Pembuat Surat (Petugas)</span>
                            <span class="fw-bold text-dark">{{ $item->pembuat_surat ?? '-' }}</span>
                        </div>
                        <div class="col-12 border-bottom pb-2">
                            <span class="text-muted small d-block">Perihal</span>
                            <span class="fw-bold text-dark fs-6">{{ $item->perihal }}</span>
                        </div>
                        <div class="col-12 border-bottom pb-2">
                            <span class="text-muted small d-block">Tujuan / Penerima</span>
                            <span class="fw-bold text-dark">{{ $item->tujuan ?? $item->tujuan_penerima ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Lampiran Berkas Physical</span>
                            @if ($item->lampiran || $item->file_surat)
                                <a href="{{ asset('storage/' . ($item->file_surat ?? $item->lampiran)) }}" target="_blank" class="fw-bold text-success text-decoration-none">
                                    <i class="bi bi-paperclip me-1"></i>Lihat / Unduh Lampiran
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        <div class="col-md-6 border-bottom pb-2">
                            <span class="text-muted small d-block">Keterangan Internal</span>
                            <span class="fw-bold text-dark">{{ $item->keterangan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RINCIAN PAYLOAD SPESIFIK (JIKA ADA) -->
            @if(is_array($p) && count($p) > 0)
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-card-list me-2 text-primary"></i>Rincian Isi Dokumen Specific</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($p as $key => $val)
                            @if(!is_array($val))
                            <div class="col-md-4 border-bottom pb-2">
                                <span class="text-muted small d-block text-capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="fw-bold text-dark">{{ $val ?: '-' }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- BOX PEMBATALAN SURAT -->
            @if (!$isMasterDefault && ($item->status ?? 'aktif') === 'aktif')
            <div class="card border-danger border-opacity-25 bg-danger bg-opacity-10 rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Surat Ini</h6>
                    <p class="small text-danger opacity-75 mb-3">
                        Gunakan fitur ini jika surat dibatalkan karena kesalahan administrasi. Nomor surat tidak akan dihapus melainkan ditandai sebagai <strong>DIBATALKAN</strong> dan tidak digunakan ulang.
                    </p>

                    <form method="POST" action="{{ route('surat-keluar.batalkan', $item->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Tuliskan alasan pembatalan surat..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Apakah Anda yakin ingin membatalkan nomor surat ini?')">
                            <i class="bi bi-x-circle me-1"></i> Konfirmasi Batalkan Surat
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection