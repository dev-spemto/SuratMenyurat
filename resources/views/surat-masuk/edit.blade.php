@extends('layouts.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @php
                $item = $suratMasuk ?? $surat;
                $jenisSurats = $jenisSurats ?? [];

                // Handling fleksibel untuk format tanggal surat
                $rawTglSurat = $item->tanggal_surat ?? $item->tgl_surat ?? null;
                if ($rawTglSurat instanceof \Carbon\Carbon) {
                    $tglSuratVal = $rawTglSurat->format('Y-m-d');
                } elseif ($rawTglSurat) {
                    $tglSuratVal = \Carbon\Carbon::parse($rawTglSurat)->format('Y-m-d');
                } else {
                    $tglSuratVal = date('Y-m-d');
                }

                // Handling fleksibel untuk format tanggal diterima
                $rawTglTerima = $item->tanggal_terima ?? $item->tanggal_diterima ?? $item->tgl_terima ?? null;
                if ($rawTglTerima instanceof \Carbon\Carbon) {
                    $tglTerimaVal = $rawTglTerima->format('Y-m-d');
                } elseif ($rawTglTerima) {
                    $tglTerimaVal = \Carbon\Carbon::parse($rawTglTerima)->format('Y-m-d');
                } else {
                    $tglTerimaVal = date('Y-m-d');
                }
            @endphp

            <!-- HEADER HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Edit Surat Masuk</h4>
                    <p class="text-muted small m-0 mt-1">Perbarui rincian informasi dan berkas surat masuk yang tersimpan.</p>
                </div>
                <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
                </a>
            </div>

            <!-- ERROR NOTIFICATION -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pengisian:</div>
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

                    <form method="POST" action="{{ route('surat-masuk.update', $item->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: INFORMASI SURAT -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                <span>Informasi Utama Surat</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nomor_surat" class="form-label small fw-bold">Nomor Surat Asal <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $item->nomor_surat) }}" required>
                                    @error('nomor_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pengirim" class="form-label small fw-bold">Pengirim / Instansi Asal <span class="text-danger">*</span></label>
                                    <input type="text" name="pengirim" id="pengirim" class="form-control @error('pengirim') is-invalid @enderror" value="{{ old('pengirim', $item->pengirim ?? $item->asal_surat) }}" required>
                                    @error('pengirim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if(count($jenisSurats) > 0)
                                <div class="col-md-6">
                                    <label for="jenis_surat_id" class="form-label small fw-bold">Jenis Surat (Opsional)</label>
                                    <select name="jenis_surat_id" id="jenis_surat_id" class="form-select @error('jenis_surat_id') is-invalid @enderror">
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($jenisSurats as $jenis)
                                            <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id', $item->jenis_surat_id) == $jenis->id)>
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
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', $tglSuratVal) }}" required>
                                    @error('tanggal_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tanggal_terima" class="form-label small fw-bold">Tanggal Diterima Sekolah <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', old('tanggal_diterima', $tglTerimaVal)) }}" required>
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
                                    <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $item->perihal) }}" required>
                                    @error('perihal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="file_surat" class="form-label small fw-bold">Ganti Scan File (Opsional)</label>
                                    <input type="file" name="file_surat" id="file_surat" class="form-control @error('file_surat') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                    
                                    @if ($item->file_surat || $item->lampiran)
                                        <div class="form-text text-success small mt-1">
                                            <i class="bi bi-file-earmark-check me-1"></i>Berkas tersimpan saat ini: 
                                            <a href="{{ asset('storage/' . ($item->file_surat ?? $item->lampiran)) }}" target="_blank" class="fw-bold">Lihat File</a>
                                        </div>
                                    @else
                                        <div class="form-text text-muted small">Biarkan kosong jika tidak ingin mengubah file scan yang ada.</div>
                                    @endif
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
                                    <input type="text" name="penerima_surat" id="penerima_surat" class="form-control @error('penerima_surat') is-invalid @enderror" value="{{ old('penerima_surat', old('penerima', $item->penerima_surat ?? $item->penerima)) }}" placeholder="Contoh: Admin TU / Nama Petugas" required>
                                    @error('penerima_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="keterangan" class="form-label small fw-bold">Keterangan / Ringkasan Disposisi</label>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan', $item->keterangan) }}" placeholder="Catatan atau disposisi tambahan jika ada...">
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER AKSI -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn btn-outline-secondary px-4 fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Perbarui Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection