@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow border-0 rounded-3 mb-4">
        
        <!-- HEADER & TOMBOL AKSI -->
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="m-0 font-weight-bold text-primary d-flex align-items-center gap-2">
                <i class="bi bi-people-fill"></i>
                <span>Master Data Pegawai / Guru</span>
            </h5>
            <div class="d-flex gap-2">
                <!-- Tombol Import Excel -->
                <button type="button" class="btn btn-outline-success btn-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalImport">
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

            <!-- TABEL DATA PEGAWAI (READ-ONLY) -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Nama & NIK</th>
                            <th>Jabatan & Satminkal</th>
                            <th>NUPTK / NIP</th>
                            <th>NRG / NBM</th>
                            <th>TTL & JK</th>
                            <th>Pendidikan & TMT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawais as $index => $p)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong class="text-dark d-block">{{ preg_replace('/\.{2,}/', '.', trim($p->nama)) }}</strong>
                                    <small class="text-muted">NIK: {{ $p->nik ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 mb-1 d-inline-block">{{ $p->jabatan ?? '-' }}</span>
                                    <small class="text-muted d-block">{{ $p->satminkal ?? 'SMP Muhammadiyah Tonjong (Induk)' }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><span class="text-muted">NUPTK:</span> {{ $p->nuptk ?? '-' }}</div>
                                        <div><span class="text-muted">NIP:</span> {{ $p->nip ?? '-' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><span class="text-muted">NRG:</span> {{ $p->nrg ?? '-' }}</div>
                                        <div><span class="text-muted">NBM:</span> {{ $p->nbm ?? '-' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $p->ttl ?? '-' }}</div>
                                        <span class="badge bg-light text-dark border">{{ $p->jenis_kelamin ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong class="text-dark">{{ $p->pendidikan_terakhir ?? '-' }}</strong></div>
                                        <div class="text-muted">TMT: {{ $p->tmt ?? $p->tgl_mulai_tugas ?? '-' }}</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data pegawai/guru. Silakan lakukan Import Excel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- MODAL IMPORT EXCEL -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Data Pegawai dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel (.xlsx / .xls)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection