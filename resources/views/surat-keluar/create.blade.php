@extends('layouts.app')

@section('title', 'Buat Surat Keluar Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @php
                $jenis = request('jenis');
                $titles = [
                    'sppd' => 'Buat SPPD & Surat Tugas',
                    'aktif_belajar' => 'Buat Surat Keterangan Aktif Belajar',
                    'aktif_mengajar' => 'Buat Surat Keterangan Aktif Mengajar',
                    'custom' => 'Buat Surat Custom / Undangan',
                ];
                $titleActive = isset($titles[$jenis]) ? $titles[$jenis] : 'Buat Surat Keluar Baru';
            @endphp

            <!-- HEADER HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">{{ $titleActive }}</h4>
                    <p class="text-muted small m-0 mt-1">Lengkapi data surat di bawah ini untuk penerbitan nomor surat otomatis.</p>
                </div>
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- ERROR NOTIFICATION -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data belum dapat disimpan:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- CARD FORMUTAR -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    <!-- PREVIEW NOMOR SURAT -->
                    <div class="bg-light p-3 rounded-3 border mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block fw-bold text-uppercase" style="font-size: 11px;">Nomor Surat Keluar</span>
                            <span class="fs-5 fw-bold text-success"><i class="bi bi-gear-wide-connected me-1"></i> Akan dibuat otomatis</span>
                        </div>
                        @if($jenis)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 fs-6 fw-semibold text-uppercase">
                                {{ str_replace('_', ' ', $jenis) }}
                            </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('surat-keluar.store') }}">
                        @csrf
                        @if($jenis)
                            <input type="hidden" name="jenis_surat_key" value="{{ $jenis }}">
                        @endif

                        <!-- SECTION 1: DATA UTAMA SURAT -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                <span>Data Utama Surat</span>
                            </h6>

                            <div class="row g-3">
                                @if(isset($jenisSurats) && count($jenisSurats) > 0)
                                <div class="col-md-6">
                                    <label for="jenis_surat_id" class="form-label small fw-bold">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="jenis_surat_id" id="jenis_surat_id" class="form-select" required>
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach ($jenisSurats as $j)
                                            <option value="{{ $j->id }}" @selected(old('jenis_surat_id') == $j->id)>
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
                                            <option value="{{ $b->id }}" @selected(old('bidang_id') == $b->id)>
                                                {{ $b->kode ?? '' }} {{ isset($b->kode) ? '-' : '' }} {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label for="tanggal_surat" class="form-label small fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                                    <div class="form-text text-muted small">Penomoran dan tahun surat akan disesuaikan dengan tanggal ini.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="lampiran" class="form-label small fw-bold">Lampiran</label>
                                    <input type="text" name="lampiran" id="lampiran" class="form-control" value="{{ old('lampiran', '-') }}" placeholder="Contoh: 1 Lembar / -">
                                </div>

                                <div class="col-12">
                                    <label for="perihal" class="form-label small fw-bold">Perihal <span class="text-danger">*</span></label>
                                    <input type="text" name="perihal" id="perihal" class="form-control" value="{{ old('perihal') }}" placeholder="Contoh: Surat Keterangan / Undangan Rapat" maxlength="255" required>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL SPESIFIK BERDASARKAN JENIS -->
                        @if($jenis === 'aktif_belajar')
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Detail Data Siswa</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[nama_siswa]" class="form-control" value="{{ old('payload.nama_siswa') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">NIS</label>
                                    <input type="text" name="payload[nis]" class="form-control" value="{{ old('payload.nis') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">NISN</label>
                                    <input type="text" name="payload[nisn]" class="form-control" value="{{ old('payload.nisn') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">NIK</label>
                                    <input type="text" name="payload[nik]" class="form-control" value="{{ old('payload.nik') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tempat, Tanggal Lahir</label>
                                    <input type="text" name="payload[ttl]" class="form-control" value="{{ old('payload.ttl') }}" placeholder="Brebes, 12 Januari 2010">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Kelas <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[kelas]" class="form-control" value="{{ old('payload.kelas') }}" placeholder="VII A" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Keperluan Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[keperluan]" class="form-control" value="{{ old('payload.keperluan') }}" placeholder="Persyaratan Beasiswa PIP" required>
                                </div>
                            </div>
                        </div>
                        @elseif($jenis === 'sppd')
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Detail Perjalanan Dinas (SPPD)</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Tempat Tujuan <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[tempat_tujuan]" class="form-control" value="{{ old('payload.tempat_tujuan') }}" placeholder="Dinas Pendidikan Kab. Brebes" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Maksud Perjalanan Dinas <span class="text-danger">*</span></label>
                                    <input type="text" name="payload[maksud_dinas]" class="form-control" value="{{ old('payload.maksud_dinas') }}" placeholder="Rakor Pengelolaan BOS" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tanggal Berangkat <span class="text-danger">*</span></label>
                                    <input type="date" name="payload[tgl_berangkat]" class="form-control" value="{{ old('payload.tgl_berangkat', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tanggal Kembali <span class="text-danger">*</span></label>
                                    <input type="date" name="payload[tgl_kembali]" class="form-control" value="{{ old('payload.tgl_kembali', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Transportasi</label>
                                    <input type="text" name="payload[transportasi]" class="form-control" value="{{ old('payload.transportasi', 'Pribadi') }}">
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- TUJUAN SURAT (STANDAR / CUSTOM) -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <span>Tujuan & Penerima Surat</span>
                            </h6>

                            <div class="form-group mb-3">
                                <label for="tujuan" class="form-label small fw-bold">Tujuan / Penerima <span class="text-danger">*</span></label>
                                <textarea name="tujuan" id="tujuan" class="form-control" rows="2" placeholder="Contoh: Kepala Dinas Pendidikan Kabupaten Brebes / Bapak Ibu Orang Tua Wali Murid" required>{{ old('tujuan') }}</textarea>
                            </div>

                            @if($jenis === 'custom')
                            <div class="form-group">
                                <label class="form-label small fw-bold">Isi Surat Custom / Undangan</label>
                                <textarea name="payload[isi_surat]" class="form-control" rows="4" placeholder="Tuliskan paragraf isi surat di sini...">{{ old('payload.isi_surat') }}</textarea>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- SECTION 3: PETUGAS & KETERANGAN TAMBAHAN -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-success d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-success text-white rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                <span>Keterangan & Petugas</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="pembuat_surat" class="form-label small fw-bold">Pembuat Surat (Petugas) <span class="text-danger">*</span></label>
                                    <input type="text" name="pembuat_surat" id="pembuat_surat" class="form-control" value="{{ old('pembuat_surat', auth()->user()->name ?? '') }}" placeholder="Nama Petugas / Admin TU" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="keterangan" class="form-label small fw-bold">Keterangan Tambahan</label>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Catatan internal (opsional)...">
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER AKSI -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary px-4 fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan & Generate Nomor
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection