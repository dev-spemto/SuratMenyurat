@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    @php
        $tahun = $tahun ?? date('Y');
        $suratKeluarTerbaru = $suratTerbaru ?? $suratKeluarTerbaru ?? [];
        $suratMasukTerbaru = $suratMasukTerbaru ?? [];
    @endphp

    <!-- WELCOME BANNER & REAL-TIME CLOCK -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-success text-white overflow-hidden position-relative">
        <!-- IKON WATERMARK BACKGROUND (Diatur opacity murni agar tidak menutupi teks/jam) -->
        <i class="bi bi-envelope-open position-absolute text-white" 
           style="font-size: 110px; right: -15px; bottom: -25px; opacity: 0.08; pointer-events: none; z-index: 1;"></i>

        <div class="card-body p-4 position-relative" style="z-index: 2;">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-7 col-lg-8">
                    <h4 class="fw-bold m-0 text-white">Selamat Datang 👋</h4>
                    <p class="text-white-50 small m-0 mt-1">Sistem Administrasi Surat Masuk & Surat Keluar SMP Muhammadiyah Tonjong — Tahun {{ $tahun }}</p>
                </div>
                <div class="col-12 col-md-5 col-lg-4 text-md-end">
                    <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-3 px-3 py-2 d-inline-block text-start text-md-end shadow-sm">
                        <div class="text-white-50 small fw-semibold" style="font-size: 11px;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $hariTanggal ?? \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="fw-bold fs-5 text-white font-monospace mt-1" id="liveClock">00:00:00 WIB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK RINGKAS PERSURATAN -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-send-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Surat Keluar Tahun {{ $tahun }}</span>
                        <h4 class="fw-bold m-0 text-dark">{{ $jumlahSurat ?? $totalSuratKeluar ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-hash"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Nomor Urut Terakhir</span>
                        <h4 class="fw-bold m-0 text-dark font-monospace">{{ sprintf('%03d', $nomorUrutTerakhir ?? $suratTerakhir?->nomor_urut ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Surat Masuk Tahun {{ $tahun }}</span>
                        <h4 class="fw-bold m-0 text-dark">{{ $jumlahSuratMasuk ?? $totalSuratMasuk ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRID TABEL SURAT TERBARU -->
    <div class="row g-4 mb-4">
        <!-- SURAT KELUAR TERBARU -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-send text-success me-2"></i>Surat Keluar Terbaru</h6>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-link text-success p-0 fw-bold text-decoration-none small">Lihat Semua &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light text-secondary text-uppercase fs-7">
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Tanggal</th>
                                <th>Perihal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratKeluarTerbaru as $surat)
                                @php
                                    $rawTgl = $surat->tanggal_surat ?? $surat->tgl_surat ?? null;
                                    $tglF = $rawTgl ? \Carbon\Carbon::parse($rawTgl)->format('d/m/Y') : '-';
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('surat-keluar.show', $surat->id) }}" class="fw-bold text-success text-decoration-none font-monospace">
                                            {{ $surat->nomor_surat }}
                                        </a>
                                    </td>
                                    <td class="text-nowrap"><i class="bi bi-calendar me-1 text-muted"></i>{{ $tglF }}</td>
                                    <td>{{ Str::limit($surat->perihal, 25) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Belum ada data surat keluar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SURAT MASUK TERBARU -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inbox text-primary me-2"></i>Surat Masuk Terbaru</h6>
                    <a href="{{ route('surat-masuk.index') }}" class="btn btn-link text-primary p-0 fw-bold text-decoration-none small">Lihat Semua &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light text-secondary text-uppercase fs-7">
                            <tr>
                                <th>Nomor Surat Asal</th>
                                <th>Pengirim</th>
                                <th>Perihal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratMasukTerbaru as $surat)
                                <tr>
                                    <td>
                                        <a href="{{ route('surat-masuk.show', $surat->id) }}" class="fw-bold text-primary text-decoration-none font-monospace">
                                            {{ $surat->nomor_surat }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($surat->pengirim ?? $surat->asal_surat ?? '-', 18) }}</td>
                                    <td>{{ Str::limit($surat->perihal, 25) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Belum ada data surat masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK PEMBUATAN DOKUMEN -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bar-chart-line-fill text-success me-2"></i>Statistik Pembuatan Dokumen</h6>
            <span class="badge bg-light text-dark border px-2 py-1 fs-7">Rekap Surat Resmi</span>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">

                <!-- SPPD & SURAT TUGAS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ route('surat.index', ['jenis' => 'sppd']) }}" class="text-decoration-none">
                        <div class="card border rounded-3 h-100 bg-light-subtle hover-shadow transition">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold d-block mb-1">SPPD & Surat Tugas</span>
                                    <h4 class="fw-bold text-success m-0">
                                        {{ $totalSppd ?? 0 }}
                                    </h4>
                                </div>
                                <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-signpost-split-fill fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- KET. AKTIF MENGAJAR -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ route('surat.index', ['jenis' => 'aktif_mengajar']) }}" class="text-decoration-none">
                        <div class="card border rounded-3 h-100 bg-light-subtle hover-shadow transition">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold d-block mb-1">Ket. Aktif Mengajar</span>
                                    <h4 class="fw-bold text-info m-0">
                                        {{ $totalAktifMengajar ?? 0 }}
                                    </h4>
                                </div>
                                <div class="bg-info text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-workspace fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- KET. AKTIF BELAJAR -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ route('surat.index', ['jenis' => 'aktif_belajar']) }}" class="text-decoration-none">
                        <div class="card border rounded-3 h-100 bg-light-subtle hover-shadow transition">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold d-block mb-1">Ket. Aktif Belajar</span>
                                    <h4 class="fw-bold text-primary m-0">
                                        {{ $totalAktifBelajar ?? 0 }}
                                    </h4>
                                </div>
                                <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-mortarboard-fill fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- CUSTOM / LAINNYA -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ route('surat.index', ['jenis' => 'custom']) }}" class="text-decoration-none">
                        <div class="card border rounded-3 h-100 bg-light-subtle hover-shadow transition">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold d-block mb-1">Custom / Lainnya</span>
                                    <h4 class="fw-bold text-warning m-0">
                                        {{ $totalCustom ?? 0 }}
                                    </h4>
                                </div>
                                <div class="bg-warning text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-file-earmark-text-fill fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- BANTUAN & KONTAK LAYANAN -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-headset me-2 text-info"></i>Bantuan & Layanan Kontak</h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengaturan->telepon ?? '085185033377') }}" target="_blank" class="p-3 border rounded-3 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-shadow">
                        <div class="bg-success text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small" style="font-size: 11px;">WhatsApp Support / IT</span>
                            <span class="fw-bold small">{{ $pengaturan->telepon ?? '085185033377' }}</span>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a href="mailto:{{ $pengaturan->email ?? 'smpmuhitonjong@gmail.com' }}" class="p-3 border rounded-3 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-shadow">
                        <div class="bg-danger text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-envelope-at-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small" style="font-size: 11px;">Email Resmi Sekolah</span>
                            <span class="fw-bold small">{{ $pengaturan->email ?? 'smpmuhitonjong@gmail.com' }}</span>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a href="{{ $pengaturan->website ?? 'https://smpmuhtonjong.sch.id' }}" target="_blank" class="p-3 border rounded-3 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-shadow">
                        <div class="bg-primary text-white rounded-3 p-2 fs-4 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small" style="font-size: 11px;">Website Resmi</span>
                            <span class="fw-bold small">{{ str_replace(['https://', 'http://'], '', $pengaturan->website ?? 'smpmuhtonjong.sch.id') }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL EXPORT PDF -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="exportModalLabel"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Export PDF Rekapitulasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="exportYear" class="form-label small fw-bold">Pilih Tahun Periode Laporan:</label>
                    <select id="exportYear" class="form-select form-select-sm">
                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" @selected($y == $tahun)>Tahun {{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <p class="text-muted small mb-3">Silakan pilih kategori rekapitulasi surat yang ingin diunduh:</p>
                <div class="d-grid gap-2">
                    <button type="button" onclick="downloadPdf('keluar')" class="btn btn-outline-success text-start p-3 rounded-3 d-flex align-items-center gap-3">
                        <i class="bi bi-send-fill fs-3 text-success"></i>
                        <div>
                            <div class="fw-bold text-dark small">Rekap Surat Keluar</div>
                            <div class="text-muted style-italic" style="font-size: 11px;">Download dokumen PDF rekap surat keluar</div>
                        </div>
                    </button>
                    <button type="button" onclick="downloadPdf('masuk')" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3">
                        <i class="bi bi-inbox-fill fs-3 text-primary"></i>
                        <div>
                            <div class="fw-bold text-dark small">Rekap Surat Masuk</div>
                            <div class="text-muted style-italic" style="font-size: 11px;">Download dokumen PDF rekap surat masuk</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PRINT LAPORAN -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="printModalLabel"><i class="bi bi-printer-fill text-secondary me-2"></i>Cetak Laporan Rekapitulasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Pilih modul rekapitulasi surat yang ingin dicetak:</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('surat-keluar.index') }}?print=true" class="btn btn-outline-success text-start p-3 rounded-3 d-flex align-items-center gap-3">
                        <i class="bi bi-printer fs-3 text-success"></i>
                        <div>
                            <div class="fw-bold text-dark small">Cetak Daftar Surat Keluar</div>
                            <div class="text-muted style-italic" style="font-size: 11px;">Buka & cetak rekapitulasi surat keluar</div>
                        </div>
                    </a>
                    <a href="{{ route('surat-masuk.index') }}?print=true" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3">
                        <i class="bi bi-printer fs-3 text-primary"></i>
                        <div>
                            <div class="fw-bold text-dark small">Cetak Daftar Surat Masuk</div>
                            <div class="text-muted style-italic" style="font-size: 11px;">Buka & cetak rekapitulasi surat masuk</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Jam Digital Real-time
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }

    setInterval(updateClock, 1000);
    updateClock();

    // Trigger Download PDF Berdasarkan Tahun Modal
    function downloadPdf(type) {
        const selectedYear = document.getElementById('exportYear').value;
        let url = '';
        
        if (type === 'keluar') {
            url = "{{ route('surat-keluar.export-pdf', ['tahun' => ':tahun']) }}".replace(':tahun', selectedYear);
        } else if (type === 'masuk') {
            url = "{{ route('surat-masuk.export-pdf', ['tahun' => ':tahun']) }}".replace(':tahun', selectedYear);
        }

        // Hide Modal via Bootstrap Instance
        const exportModalEl = document.getElementById('exportModal');
        const modalInstance = bootstrap.Modal.getInstance(exportModalEl);
        if (modalInstance) {
            modalInstance.hide();
        }

        window.location.href = url;
    }
</script>
@endpush