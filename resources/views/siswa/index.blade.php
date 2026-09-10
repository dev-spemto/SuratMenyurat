@extends('layouts.app')

@php
    // Helper fungsi untuk generate URL Sorting + Icon Panah Interaktif
    if (!function_exists('sortLink')) {
        function sortLink($column, $label, $currentSortBy, $currentSortDir) {
            $isCurrent = ($currentSortBy === $column);
            $nextDir = ($isCurrent && $currentSortDir === 'asc') ? 'desc' : 'asc';

            // Menjaga query string yang sudah ada (seperti keyword pencarian 'search')
            $url = route('siswa.index', array_merge(request()->query(), [
                'sort_by' => $column,
                'sort_dir' => $nextDir
            ]));

            // Kondisi Icon Panah
            if ($isCurrent) {
                $icon = $currentSortDir === 'asc' 
                    ? '<i class="bi bi-sort-alpha-down text-primary fw-bold ms-1"></i>' 
                    : '<i class="bi bi-sort-alpha-down-alt text-primary fw-bold ms-1"></i>';
            } else {
                $icon = '<i class="bi bi-arrow-down-up text-muted opacity-50 ms-1 small"></i>';
            }

            return '<a href="' . $url . '" class="text-decoration-none text-dark d-inline-flex align-items-center hover-link">' . $label . ' ' . $icon . '</a>';
        }
    }
@endphp

@section('content')
<div class="container-fluid">
    <div class="card shadow border-0 rounded-3 mb-4">
        
        <!-- HEADER & TOMBOL AKSI -->
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="m-0 font-weight-bold text-primary d-flex align-items-center gap-2">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Master Data Siswa</span>
            </h5>
            <div class="d-flex gap-2">
                <!-- Tombol Import Excel -->
                <button type="button" class="btn btn-outline-success btn-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Import Excel</span>
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            
            <!-- ALERT SUCCESS / ERROR -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- TABEL DATA SISWA (READ-ONLY) -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center py-3">
                                {!! sortLink('id', 'No', $sortBy ?? 'id', $sortDir ?? 'asc') !!}
                            </th>
                            <th class="py-3">
                                {!! sortLink('nis', 'NIS / NISN / NIK', $sortBy ?? 'id', $sortDir ?? 'asc') !!}
                            </th>
                            <th class="py-3">
                                {!! sortLink('nama', 'Nama Siswa', $sortBy ?? 'id', $sortDir ?? 'asc') !!}
                            </th>
                            <th class="py-3 text-center">
                                {!! sortLink('jenis_kelamin', 'L/P', $sortBy ?? 'id', $sortDir ?? 'asc') !!}
                            </th>
                            <th class="py-3 text-center">
                                {!! sortLink('kelas', 'Kelas', $sortBy ?? 'id', $sortDir ?? 'asc') !!}
                            </th>
                            <th class="py-3">TTL</th>
                            <th class="py-3">Nama Orang Tua / Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            <tr>
                                <td class="text-center">
                                    {{ method_exists($siswas, 'firstItem') ? $siswas->firstItem() + $index : $index + 1 }}
                                </td>
                                <td>
                                    <div class="small">
                                        <div><span class="text-muted">NIS:</span> {{ $siswa->nis ?? '-' }}</div>
                                        <div><span class="text-muted">NISN:</span> {{ $siswa->nisn ?? '-' }}</div>
                                        <div><span class="text-muted">NIK:</span> {{ $siswa->nik ?? '-' }}</div>
                                    </div>
                                </td>
                                <td><strong class="text-dark">{{ $siswa->nama }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $siswa->jenis_kelamin }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $siswa->kelas }}</span>
                                </td>
                                <td><small>{{ $siswa->ttl ?? '-' }}</small></td>
                                <td>
                                    <div class="small">
                                        <div>
                                            <strong class="text-dark">
                                                @php
                                                    $ortuList = array_filter([
                                                        trim($siswa->nama_ayah ?? ''),
                                                        trim($siswa->nama_ibu ?? '')
                                                    ]);
                                                    
                                                    if (!empty($ortuList)) {
                                                        $displayOrtu = implode(' / ', $ortuList);
                                                    } else {
                                                        $rawOrtu = trim($siswa->nama_orang_tua ?? '');
                                                        if (!empty($rawOrtu) && $rawOrtu !== '-') {
                                                            $parts = preg_split('/\s*(\/|,|&|\bdan\b)\s*/i', $rawOrtu);
                                                            $partsClean = array_values(array_filter(array_map('trim', $parts)));
                                                            $displayOrtu = count($partsClean) > 1 ? implode(' / ', $partsClean) : $rawOrtu;
                                                        } else {
                                                            $displayOrtu = '-';
                                                        }
                                                    }
                                                @endphp
                                                {{ $displayOrtu }}
                                            </strong>
                                        </div>
                                        <div class="text-muted">{{ $siswa->alamat ?? '-' }}</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data siswa. Silakan lakukan Import Excel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($siswas, 'links'))
                <div class="mt-3">
                    {{ $siswas->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<!-- MODAL IMPORT EXCEL -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Data Siswa dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel (.xlsx / .xls)</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                    <small class="text-muted d-block bg-light p-2 rounded border">
                        Pastikan baris header pertama di file Excel berisi nama kolom: <br>
                        <code>nis</code>, <code>nisn</code>, <code>nik</code>, <code>nama</code>, <code>ttl</code>, <code>jenis_kelamin</code>, <code>kelas</code>, <code>nama_ayah</code>, <code>nama_ibu</code>, <code>nama_orang_tua</code>, <code>alamat</code>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection