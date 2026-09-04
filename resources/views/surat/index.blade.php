@extends('layouts.app')

@section('content')
<div class="row g-4">

    @php
        // 1. Deteksi Kategori Surat Aktif dari Parameter URL (?jenis=)
        $currentJenis = request('jenis');

        // Config Teks & Ikon Dinamis per Kategori
        $configMap = [
            'sppd' => [
                'title' => 'SPPD & Surat Tugas',
                'icon' => 'bi-signpost-split-fill text-info',
                'btn_label' => 'Buat SPPD Baru',
                'stat_label' => 'Total SPPD & Tugas',
                'stat_count' => $totalSppd ?? 0,
                'stat_color' => 'text-info',
                'stat_bg' => 'bg-info text-info',
                'table_title' => 'Riwayat Perjalanan Dinas (SPPD)',
                'col_header' => 'MAKSUD DINAS',
            ],
            'aktif_belajar' => [
                'title' => 'Surat Keterangan Aktif Belajar',
                'icon' => 'bi-mortarboard-fill text-success',
                'btn_label' => 'Buat Ket. Aktif Belajar',
                'stat_label' => 'Total Aktif Belajar',
                'stat_count' => $totalAktifBelajar ?? 0,
                'stat_color' => 'text-success',
                'stat_bg' => 'bg-success text-success',
                'table_title' => 'Riwayat Surat Aktif Belajar',
                'col_header' => 'NAMA SISWA / PERIHAL',
            ],
            'aktif_mengajar' => [
                'title' => 'Surat Keterangan Aktif Mengajar',
                'icon' => 'bi-person-workspace text-warning',
                'btn_label' => 'Buat Ket. Aktif Mengajar',
                'stat_label' => 'Total Aktif Mengajar',
                'stat_count' => $totalAktifMengajar ?? 0,
                'stat_color' => 'text-warning',
                'stat_bg' => 'bg-warning text-warning',
                'table_title' => 'Riwayat Surat Aktif Mengajar',
                'col_header' => 'NAMA GURU / PERIHAL',
            ],
            'custom' => [
                'title' => 'Surat Custom / Lainnya',
                'icon' => 'bi-file-earmark-text-fill text-secondary',
                'btn_label' => 'Buat Surat Custom',
                'stat_label' => 'Total Surat Custom',
                'stat_count' => $totalCustom ?? 0,
                'stat_color' => 'text-secondary',
                'stat_bg' => 'bg-secondary text-secondary',
                'table_title' => 'Riwayat Surat Custom',
                'col_header' => 'PERIHAL',
            ],
        ];

        // Fallback jika di Dashboard Utama (tanpa parameter ?jenis=)
        $currentConfig = $configMap[$currentJenis] ?? [
            'title' => 'Dashboard Penomoran Surat',
            'icon' => 'bi-speedometer2 text-primary',
            'btn_label' => 'Buat Surat Baru',
            'stat_label' => 'Total Surat Keluar',
            'stat_count' => $totalSuratGlobal ?? 0,
            'stat_color' => 'text-primary',
            'stat_bg' => 'bg-primary text-primary',
            'table_title' => 'Semua Riwayat Penomoran Surat',
            'col_header' => 'PERIHAL / KETERANGAN',
        ];

        $search = request('search');
        $tahun  = request('tahun');
    @endphp

    <!-- ALERT NOTIFIKASI SUCCESS -->
    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 m-0" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- HEADER DASHBOARD & TOMBOL AKSI DINAMIS -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 p-md-4 rounded-3 shadow-sm border">
            <div>
                <h4 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi {{ $currentConfig['icon'] }}"></i>
                    <span>{{ $currentConfig['title'] }}</span>
                </h4>
                <p class="text-muted small m-0 mt-1">Kelola arsip penomoran surat keluar dan cetak dokumen resmi sekolah.</p>
            </div>
            @if($currentJenis)
                <a href="{{ route('surat.create', ['jenis' => $currentJenis]) }}" class="btn btn-primary fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg fs-6"></i>
                    <span>{{ $currentConfig['btn_label'] }}</span>
                </a>
            @endif
        </div>
    </div>

    <!-- CARDS STATISTIK (2 CARD DINAMIS) -->
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-md-4">
                <div>
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Total Surat (Global)</span>
                    <h3 class="fw-bold m-0 text-dark">{{ $totalSuratGlobal ?? 0 }}</h3>
                </div>
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-envelope-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-md-4">
                <div>
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">{{ $currentConfig['stat_label'] }}</span>
                    <h3 class="fw-bold m-0 {{ $currentConfig['stat_color'] }}">{{ $currentConfig['stat_count'] }}</h3>
                </div>
                <div class="rounded-3 {{ $currentConfig['stat_bg'] }} bg-opacity-10 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi {{ $currentConfig['icon'] }} fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & PENCARIAN DINAMIS -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('surat.index', array_filter(['jenis' => $currentJenis])) }}" method="GET" class="row g-3">
                    @if($currentJenis)
                        <!-- Pertahankan jenis surat aktif di URL saat Submit Filter -->
                        <input type="hidden" name="jenis" value="{{ $currentJenis }}">
                    @endif

                    <div class="col-12 col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nomor surat, penerima, atau perihal..." value="{{ $search }}">
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <select name="tahun" class="form-select">
                            <option value="">-- Semua Tahun --</option>
                            @if(isset($tahunList))
                                @foreach($tahunList as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-6 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 fw-semibold">
                            <i class="bi bi-filter me-1"></i>Filter
                        </button>
                        @if($search || $tahun)
                            <a href="{{ route('surat.index', array_filter(['jenis' => $currentJenis])) }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TABEL RIWAYAT DINAMIS -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-journal-text {{ $currentConfig['stat_color'] }}"></i>
                    <span>{{ $currentConfig['table_title'] }}</span>
                </h6>
                <small class="text-muted">Menampilkan: {{ method_exists($suratList, 'total') ? $suratList->total() : $suratList->count() }} Data</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                            <tr>
                                <th class="text-center py-3 px-3" style="width: 50px;">NO</th>
                                <th class="py-3 px-3">NOMOR SURAT</th>
                                <th class="py-3 px-3">PENERIMA</th>
                                <th class="py-3 px-3">{{ $currentConfig['col_header'] }}</th>
                                <th class="py-3 px-3">TANGGAL</th>
                                <th class="text-center py-3 px-3" style="width: 220px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($suratList as $index => $surat)
                                @php 
                                    $p = $surat->payload_detail ?? []; 
                                    
                                    // DETEKSI DATA SAMPLE MASTER SYSTEM (VIEW ONLY)
                                    $isMasterSample = ((int)$surat->tahun === 2000) 
                                                      || ((int)$surat->nomor_urut === 0) 
                                                      || \Illuminate\Support\Str::startsWith($surat->nomor_surat, '000/');
                                @endphp
                                <tr>
                                    <td class="text-center text-muted fw-semibold py-3 px-3">
                                        {{ method_exists($suratList, 'firstItem') ? $suratList->firstItem() + $index : $index + 1 }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="fw-bold text-primary">{{ $surat->nomor_surat }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="fw-semibold text-dark">{{ $surat->tujuan_penerima ?? $surat->tujuan ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="text-muted d-block text-truncate" style="max-width: 250px;">
                                            {{ $p['maksud_dinas'] ?? $p['nama_guru'] ?? $p['nama_siswa'] ?? $surat->perihal }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-muted">
                                        {{ \Carbon\Carbon::parse($surat->tgl_surat ?? $surat->tanggal_surat)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="text-center py-3 px-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <!-- Print / Preview (Selalu Ada) -->
                                            <a href="{{ route('surat.print', $surat->id) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" title="Cetak Langsung">
                                                <i class="bi bi-printer-fill"></i>
                                                <span>Print</span>
                                            </a>

                                            <!-- Export PDF (Selalu Ada) -->
                                            <a href="{{ route('surat.print', $surat->id) }}?download=pdf" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" title="Export PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                                <span>PDF</span>
                                            </a>

                                            {{-- TOMBOL EDIT & HAPUS HANYA TAMPIL JIKA BUKAN MASTER SAMPLE --}}
                                            @if(!$isMasterSample)
                                                <!-- Edit Surat -->
                                                <a href="{{ route('surat.edit', $surat->id) }}" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1" title="Edit Surat">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Edit</span>
                                                </a>

                                                <!-- Form Hapus Surat -->
                                                <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat nomor {{ $surat->nomor_surat }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Hapus Surat">
                                                        <i class="bi bi-trash-fill text-danger"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        <p class="m-0 fw-semibold">Belum ada dokumen yang ditemukan pada kategori ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($suratList, 'hasPages') && $suratList->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $suratList->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection