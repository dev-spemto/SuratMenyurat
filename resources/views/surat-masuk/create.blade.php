@extends('layouts.app')

@section('title', 'Catat Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <!-- HEADER HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Catat Surat Masuk Baru</h4>
                    <p class="text-muted small m-0 mt-1">Masukkan rincian informasi dan upload berkas fisik surat masuk yang diterima.</p>
                </div>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- ERROR NOTIFICATION -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data belum dapat disimpan:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- CARD FORMULIR -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- SECTION 1: INFORMASI SURAT -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                <span>Informasi Utama Surat</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nomor_surat" class="form-label small fw-bold">Nomor Surat Asal <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat') }}" placeholder="Contoh: 005/DISDIK/VIII/2026" required>
                                    @error('nomor_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pengirim" class="form-label small fw-bold">Pengirim / Instansi Asal <span class="text-danger">*</span></label>
                                    <input type="text" name="pengirim" id="pengirim" class="form-control @error('pengirim') is-invalid @enderror" value="{{ old('pengirim', old('asal_surat')) }}" placeholder="Contoh: Dinas Pendidikan Cabang Brebes" required>
                                    @error('pengirim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if(isset($jenisSurats) && count($jenisSurats) > 0)
                                <div class="col-md-6">
                                    <label for="jenis_surat_id" class="form-label small fw-bold">Jenis Surat (Opsional)</label>
                                    <select name="jenis_surat_id" id="jenis_surat_id" class="form-select @error('jenis_surat_id') is-invalid @enderror">
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($jenisSurats as $jenis)
                                            <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>
                                                {{ $jenis->kode ?? '' }} {{ isset($jenis->kode) ? '-' : '' }} {{ $jenis->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_surat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label for="tanggal_surat" class="form-label small fw-bold">Tanggal Terbit Surat <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                                    @error('tanggal_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tanggal_terima" class="form-label small fw-bold">Tanggal Diterima Sekolah <span class="text-danger">*</span></label>
                                    <!-- NAMA ATRIBUT DISESUAIKAN MENJADI tanggal_terima -->
                                    <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', old('tanggal_diterima', date('Y-m-d'))) }}" required>
                                    @error('tanggal_terima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PERIHAL & BERKAS SCAN -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Perihal & Lampiran File</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="perihal" class="form-label small fw-bold">Perihal Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" placeholder="Contoh: Undangan Rapat Koordinasi BOS" required>
                                    @error('perihal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="file_surat" class="form-label small fw-bold">Upload Scan Dokumen (Opsional)</label>
                                    <input type="file" name="file_surat" id="file_surat" class="form-control @error('file_surat') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text text-muted small">Format yang didukung: PDF, JPG, PNG (Maksimal 2MB).</div>
                                    @error('file_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: PETUGAS & DISPOSISI -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                <span>Pengarsipan & Disposisi</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="penerima_surat" class="form-label small fw-bold">Penerima / Petugas TU <span class="text-danger">*</span></label>
                                    <!-- NAMA ATRIBUT DISESUAIKAN MENJADI penerima_surat -->
                                    <input type="text" name="penerima_surat" id="penerima_surat" class="form-control @error('penerima_surat') is-invalid @enderror" value="{{ old('penerima_surat', old('penerima', auth()->user()->name ?? 'Admin TU')) }}" placeholder="Nama petugas penerima fisik surat" required>
                                    @error('penerima_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="keterangan" class="form-label small fw-bold">Keterangan / Ringkasan Disposisi</label>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}" placeholder="Catatan internal atau perintah disposisi (opsional)...">
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER AKSI -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary px-4 fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Surat Masuk
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection