@extends('layouts.app')

@section('title', 'Daftar Surat Masuk')

@section('content')
<div class="container-fluid">

    @php
        $items = $suratMasuks ?? $surats;
    @endphp

    <!-- HEADER HALAMAN & AKSI UTAMA -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark">Arsip Surat Masuk</h4>
            <p class="text-muted small m-0 mt-1">Kelola seluruh pendataan dan berkas fisik surat masuk sekolah.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- FORM EXPORT PDF PER TAHUN -->
            <form action="{{ route('surat-masuk.export-pdf') }}" method="GET" class="d-flex gap-1 align-items-center">
                <select name="tahun" class="form-select form-select-sm" style="width: 100px;">
                    @for ($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" @selected(request('tahun', date('Y')) == $i)>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-danger btn-sm fw-semibold text-nowrap">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Rekap PDF
                </button>
            </form>

            <a href="{{ route('surat-masuk.create') }}" class="btn btn-success btn-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Catat Surat Masuk
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI FLASH -->
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

    <!-- FILTER UNIFORM -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('surat-masuk.index') }}" class="row g-2 align-items-end">
                
                {{-- Search Box --}}
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label small fw-bold text-muted mb-1">Cari Keyword</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            class="form-control" 
                            value="{{ request('search', request('cari')) }}" 
                            placeholder="Nomor surat, pengirim, atau perihal..."
                        >
                    </div>
                </div>

                {{-- Dropdown Tahun --}}
                <div class="col-6 col-md-2">
                    <label for="tahun" class="form-label small fw-bold text-muted mb-1">Tahun</label>
                    <select id="tahun" name="tahun" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" @selected(request('tahun') == $i)>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Dropdown Jenis Surat --}}
                @if(isset($jenisSurats) && count($jenisSurats) > 0)
                <div class="col-6 col-md-3">
                    <label for="jenis_surat_id" class="form-label small fw-bold text-muted mb-1">Jenis Surat</label>
                    <select name="jenis_surat_id" id="jenis_surat_id" class="form-select form-select-sm">
                        <option value="">-- Semua Jenis Surat --</option>
                        @foreach ($jenisSurats as $jenis)
                            <option value="{{ $jenis->id }}" @selected(request('jenis_surat_id') == $jenis->id)>
                                {{ $jenis->kode ?? '' }} {{ isset($jenis->kode) ? '-' : '' }} {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if (request()->hasAny(['search', 'cari', 'tahun', 'jenis_surat_id']))
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary btn-sm text-nowrap">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inbox me-2 text-primary"></i>Daftar Surat Masuk</h6>
            <span class="badge bg-light text-dark border px-2 py-1 fs-7">
                Total: {{ method_exists($items, 'total') ? $items->total() : count($items) }} Surat
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="table-light text-secondary text-uppercase fs-7">
                    <tr>
                        <th class="ps-3" style="width: 20%;">Nomor Surat Asal</th>
                        <th style="width: 15%;">Jenis / Pengirim</th>
                        <th>Perihal Surat</th>
                        <th style="width: 11%;">Tgl. Surat</th>
                        <th style="width: 11%;">Tgl. Diterima</th>
                        <th class="text-center" style="width: 10%;">Scan File</th>
                        <th class="text-end pe-3" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        @php
                            // Format Tanggal Surat Safe Handling
                            $rawTglSurat = $item->tanggal_surat ?? $item->tgl_surat ?? null;
                            if ($rawTglSurat instanceof \Carbon\Carbon) {
                                $tglSuratF = $rawTglSurat->format('d/m/Y');
                            } elseif ($rawTglSurat) {
                                $tglSuratF = \Carbon\Carbon::parse($rawTglSurat)->format('d/m/Y');
                            } else {
                                $tglSuratF = '-';
                            }

                            // Format Tanggal Diterima Safe Handling
                            $rawTglTerima = $item->tanggal_diterima ?? $item->tanggal_terima ?? $item->tgl_terima ?? null;
                            if ($rawTglTerima instanceof \Carbon\Carbon) {
                                $tglTerimaF = $rawTglTerima->format('d/m/Y');
                            } elseif ($rawTglTerima) {
                                $tglTerimaF = \Carbon\Carbon::parse($rawTglTerima)->format('d/m/Y');
                            } else {
                                $tglTerimaF = '-';
                            }

                            // Proteksi Khusus Master Default System (Tahun 2000 & Nomor Urut 0)
                            $isMasterDefault = (($item->tahun == 2000 && $item->nomor_urut == 0) || $item->nomor_surat === '000/SMP/SAMPLE/2000');
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('surat-masuk.show', $item->id) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $item->nomor_surat }}
                                </a>
                                @if(isset($item->nomor_urut))
                                    <div class="text-muted small" style="font-size: 11px;">
                                        Urut: {{ sprintf('%03d', $item->nomor_urut) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $item->pengirim ?? $item->asal_surat ?? '-' }}
                                </div>
                                @if($item->jenisSurat)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle mt-1" style="font-size: 10px;">
                                        {{ $item->jenisSurat->kode }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->perihal }}</div>
                            </td>
                            <td>
                                <i class="bi bi-calendar-event me-1 text-muted"></i>{{ $tglSuratF }}
                            </td>
                            <td>
                                <i class="bi bi-box-arrow-in-down me-1 text-success"></i>{{ $tglTerimaF }}
                            </td>
                            <td class="text-center">
                                @if ($item->file_surat || $item->lampiran)
                                    <a href="{{ asset('storage/' . ($item->file_surat ?? $item->lampiran)) }}" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 text-decoration-none" title="Lihat Berkas">
                                        <i class="bi bi-paperclip me-1"></i>Ada File
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- TOMBOL VIEW (Selalu Muncul) -->
                                    <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if (!$isMasterDefault)
                                        <!-- TOMBOL EDIT -->
                                        <a href="{{ route('surat-masuk.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit Surat">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- TOMBOL HAPUS -->
                                        <form action="{{ route('surat-masuk.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat masuk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Surat">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <div class="fw-bold text-dark">Belum ada data surat masuk</div>
                                <p class="small m-0">Tidak ada arsip surat masuk yang sesuai dengan parameter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($items, 'hasPages') && $items->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>

</div>
@endsection