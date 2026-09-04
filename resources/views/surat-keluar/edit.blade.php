@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @php
                $item = $surat ?? $suratKeluar;
                $p = $item->payload_detail ?? [];
                $jenis = $item->jenis_surat ?? request('jenis', 'custom');
                $tglSurat = isset($item->tgl_surat) ? $item->tgl_surat : ($item->tanggal_surat ?? null);
                if ($tglSurat instanceof \Carbon\Carbon) {
                    $tglSuratFormatted = $tglSurat->format('Y-m-d');
                } elseif (!empty($tglSurat)) {
                    $tglSuratFormatted = \Carbon\Carbon::parse($tglSurat)->format('Y-m-d');
                } else {
                    $tglSuratFormatted = date('Y-m-d');
                }
            @endphp

            <!-- HEADER HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Edit Surat Keluar</h4>
                    <p class="text-muted small m-0 mt-1">Perbarui data detail surat tanpa mengubah nomor surat yang telah terbit.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('surat-keluar.show', $item->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
                    </a>
                </div>
            </div>

            <!-- ERROR NOTIFICATION -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data belum dapat diperbarui:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- NOMOR SURAT CARD -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block fw-bold text-uppercase" style="font-size: 11px;">Nomor Surat (Terkunci)</span>
                        <span class="fs-5 fw-bold text-primary">{{ $item->nomor_surat }}</span>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 fs-6 fw-semibold text-uppercase">
                        {{ str_replace('_', ' ', $jenis) }}
                    </span>
                </div>
            </div>

            <!-- CARD FORMUTAR -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('surat-keluar.update', $item->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: DATA UTAMA SURAT -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-primary d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                <span>Data Utama Surat</span>
                            </h6>

                            <div class="row g-3">
                                @if(isset($jenisSurats) && count($jenisSurats) > 0)
                                <div class="col-md-6">
                                    <label for="jenis_surat_id" class="form-label small fw-bold">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="jenis_surat_id" id="jenis_surat_id" class="form-select" required>
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($jenisSurats as $j)
                                            <option value="{{ $j->id }}" @selected(old('jenis_surat_id', $item->jenis_surat_id) == $j->id)>
                                                {{ $j->kode ?? '' }} {{ isset($j->kode) ? '-' : '' }} {{ $j->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if(isset($bidangs) && count($bidangs) > 0)
                                <div class="col-md-6">
                                    <label for="bidang_id" class="form-label small fw-bold">Bidang / Sub-Bagian <span class="text-danger">*</span></label>
                                    <select name="bidang_id" id="bidang_id" class="form-select" required>
                                        <option value="">-- Pilih Bidang --</option>
                                        @foreach ($bidangs as $b)
                                            <option value="{{ $b->id }}" @selected(old('bidang_id', $item->bidang_id) == $b->id)>
                                                {{ $b->kode ?? '' }} {{ isset($b->kode) ? '-' : '' }} {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label for="tanggal_surat" class="form-label small fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $tglSuratFormatted) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="lampiran" class="form-label small fw-bold">Lampiran</label>
                                    <input type="text" name="lampiran" id="lampiran" class="form-control" value="{{ old('lampiran', $item->lampiran ?? '-') }}">
                                </div>

                                <div class="col-12">
                                    <label for="perihal" class="form-label small fw-bold">Perihal <span class="text-danger">*</span></label>
                                    <input type="text" name="perihal" id="perihal" class="form-control" value="{{ old('perihal', $item->perihal) }}" maxlength="255" required>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL SPESIFIK PAYLOAD -->
                        @if($jenis === 'aktif_belajar')
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Detail Data Siswa</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[nama_siswa]" class="form-control" value="{{ old('payload.nama_siswa', $p['nama_siswa'] ?? '') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">NIS</label>
                                    <input type="text" name="payload[nis]" class="form-control" value="{{ old('payload.nis', $p['nis'] ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">NISN</label>
                                    <input type="text" name="payload[nisn]" class="form-control" value="{{ old('payload.nisn', $p['nisn'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">NIK</label>
                                    <input type="text" name="payload[nik]" class="form-control" value="{{ old('payload.nik', $p['nik'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tempat, Tanggal Lahir</label>
                                    <input type="text" name="payload[ttl]" class="form-control" value="{{ old('payload.ttl', $p['ttl'] ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Kelas <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[kelas]" class="form-control" value="{{ old('payload.kelas', $p['kelas'] ?? '') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Keperluan Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[keperluan]" class="form-control" value="{{ old('payload.keperluan', $p['keperluan'] ?? '') }}" required>
                                </div>
                            </div>
                        </div>
                        @elseif($jenis === 'sppd')
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-primary d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Detail Perjalanan Dinas (SPPD)</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Tempat Tujuan <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[tempat_tujuan]" class="form-control" value="{{ old('payload.tempat_tujuan', $p['tempat_tujuan'] ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Maksud Perjalanan Dinas <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[maksud_dinas]" class="form-control" value="{{ old('payload.maksud_dinas', $p['maksud_dinas'] ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tanggal Berangkat <span class="text-danger">*</span></label>
                                    <input type="date" name="payload[tgl_berangkat]" class="form-control" value="{{ old('payload.tgl_berangkat', $p['tgl_berangkat'] ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tanggal Kembali <span class="text-danger">*</span></label>
                                    <input type="date" name="payload[tgl_kembali]" class="form-control" value="{{ old('payload.tgl_kembali', $p['tgl_kembali'] ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Transportasi</label>
                                    <input type="text" name="payload[transportasi]" class="form-control" value="{{ old('payload.transportasi', $p['transportasi'] ?? 'Pribadi') }}">
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- TUJUAN SURAT (STANDAR / CUSTOM) -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-primary d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Tujuan & Penerima Surat</span>
                            </h6>

                            <div class="form-group mb-3">
                                <label for="tujuan" class="form-label small fw-bold">Tujuan / Penerima <span class="text-danger">*</span></label>
                                <textarea name="tujuan" id="tujuan" class="form-control" rows="2" required>{{ old('tujuan', $item->tujuan ?? $item->tujuan_penerima ?? '') }}</textarea>
                            </div>

                            @if($jenis === 'custom')
                            <div class="form-group">
                                <label class="form-label small fw-bold">Isi Surat Custom / Undangan</label>
                                <textarea name="payload[isi_surat]" class="form-control" rows="4">{{ old('payload.isi_surat', $p['isi_surat'] ?? '') }}</textarea>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- SECTION 3: PETUGAS & KETERANGAN TAMBAHAN -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                <span>Keterangan & Petugas</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="pembuat_surat" class="form-label small fw-bold">Pembuat Surat (Petugas) <span class="text-danger">*</span></label>
                                    <input type="text" name="pembuat_surat" id="pembuat_surat" class="form-control" value="{{ old('pembuat_surat', $item->pembuat_surat ?? auth()->user()->name ?? '') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="keterangan" class="form-label small fw-bold">Keterangan Tambahan</label>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan', $item->keterangan ?? '') }}" placeholder="Catatan internal (opsional)...">
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER AKSI -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('surat-keluar.show', $item->id) }}" class="btn btn-outline-secondary px-4 fw-semibold">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection