@extends('layouts.app')

@section('title', 'Download Format Surat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark">Format Surat Siap Pakai</h4>
            <p class="text-muted small m-0 mt-1">Simpan dan unduh template/format surat resmi sekolah (.docx, .pdf, .xlsx)</p>
        </div>
        <!-- TOMBOL UPLOAD MODAL -->
        <button type="button" class="btn btn-success btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-upload me-1"></i> Upload Format Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-folder-symlink-fill text-success me-2"></i>Daftar Berkas Format Surat</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @forelse($files as $file)
                    @php
                        $filename = basename($file);
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        
                        // Icon berdasarkan ekstensi
                        $iconClass = 'bi-file-earmark-word-fill text-primary';
                        if (in_array($ext, ['pdf'])) $iconClass = 'bi-file-earmark-pdf-fill text-danger';
                        if (in_array($ext, ['xlsx', 'xls'])) $iconClass = 'bi-file-earmark-excel-fill text-success';
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border rounded-3 p-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi {{ $iconClass }} fs-1"></i>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold m-0 text-truncate" title="{{ $filename }}">{{ $filename }}</h6>
                                    <span class="text-muted small text-uppercase">{{ $ext }} File</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/format-surat/' . $filename) }}" download class="btn btn-primary btn-sm flex-fill fw-bold">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                                <form action="{{ route('format-surat.destroy', $filename) }}" method="POST" onsubmit="return confirm('Hapus format surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                        <span>Belum ada format surat yang diunggah. Silakan klik tombol <strong>Upload Format Baru</strong>.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold"><i class="bi bi-upload me-2 text-success"></i>Upload Format Surat</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('format-surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih File Master / Template Surat:</label>
                        <input type="file" name="file_format" class="form-control form-control-sm" accept=".docx,.doc,.pdf,.xlsx,.xls" required>
                        <span class="text-muted small d-block mt-1" style="font-size: 11px;">Format yang didukung: .docx, .doc, .pdf, .xlsx (Maks. 10MB)</span>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold">Upload File</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection