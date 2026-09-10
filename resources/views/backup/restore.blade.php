@extends('layouts.app')

@section('title', 'Restore Database - SMP Muhammadiyah Tonjong')
@section('page-title', 'Restore Database')

@section('content')
<div class="container-fluid" style="max-width: 700px;">
    
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <h5 class="m-0 font-weight-bold text-danger d-flex align-items-center gap-2">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Restore Database Aplikasi</span>
            </h5>
        </div>

        <div class="card-body p-4">
            <p class="text-muted">
                Gunakan fitur ini untuk mengembalikan database aplikasi dari file cadangan (backup).
            </p>

            {{-- Pesan Error Validation --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Restore gagal:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Peringatan Kritis --}}
            <div class="alert alert-warning border-warning border-start border-4 rounded-3 p-3 mb-4">
                <div class="d-flex gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                    <div>
                        <strong>Perhatian!</strong><br>
                        Database saat ini akan diganti sepenuhnya dengan data dari file backup yang Anda unggah.
                        Sistem akan membuat cadangan otomatis sebelum proses restore dijalankan.
                    </div>
                </div>
            </div>

            {{-- Form Upload --}}
            <form method="POST" action="{{ route('backup.restore') }}" enctype="multipart/form-data" onsubmit="return confirm('Yakin ingin melakukan restore database? Data saat ini akan ditimpa.');">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Pilih File Backup (.sqlite / .db)</label>
                    <input type="file" name="database" class="form-control" accept=".sqlite,.db" required>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-light border w-50">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-danger fw-bold w-50">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> RESTORE DATABASE
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection