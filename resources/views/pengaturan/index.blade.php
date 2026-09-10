@extends('layouts.app')

@section('title', 'Pengaturan Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <!-- HEADER HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Identitas Sekolah & Kop Surat</h4>
                    <p class="text-muted small m-0 mt-1">Data berikut digunakan sebagai identitas resmi sekolah pada sistem dan tata letak Kop Surat.</p>
                </div>
            </div>

            <!-- NOTIFIKASI BERHASIL -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- NOTIFIKASI ERROR -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada formulir:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data" id="formPengaturan">
                @csrf
                @method('PUT')

                <!-- SECTION 1: IDENTITAS SEKOLAH & YAYASAN -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-building me-2 text-success"></i>1. Identitas Utama & Naungan Organisasi</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nama_majelis" class="form-label small fw-bold">Nama Majelis / Lembaga Naungan <span class="text-danger">*</span></label>
                                <input type="text" id="nama_majelis" name="nama_majelis" class="form-control field-input" value="{{ old('nama_majelis', $pengaturan?->nama_majelis ?? 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL') }}" disabled required>
                            </div>

                            <div class="col-12">
                                <label for="pimpinan" class="form-label small fw-bold">Pimpinan Daerah / Cabang <span class="text-danger">*</span></label>
                                <input type="text" id="pimpinan" name="pimpinan" class="form-control field-input" value="{{ old('pimpinan', $pengaturan?->pimpinan ?? 'PIMPINAN DAERAH MUHAMMADIYAH BREBES') }}" disabled required>
                            </div>

                            <div class="col-12">
                                <label for="nama_sekolah" class="form-label small fw-bold">Nama Sekolah <span class="text-danger">*</span></label>
                                <input type="text" id="nama_sekolah" name="nama_sekolah" class="form-control field-input" value="{{ old('nama_sekolah', $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG') }}" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label for="npsn" class="form-label small fw-bold">NPSN Sekolah <span class="text-danger">*</span></label>
                                <input type="text" id="npsn" name="npsn" class="form-control field-input" value="{{ old('npsn', $pengaturan?->npsn ?? '20326564') }}" placeholder="Nomor Pokok Sekolah Nasional" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label for="nss" class="form-label small fw-bold">NSS / NDS Sekolah</label>
                                <input type="text" id="nss" name="nss" class="form-control field-input" value="{{ old('nss', $pengaturan?->nss ?? '202032906045') }}" placeholder="Nomor Statistik Sekolah" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: ALAMAT & KONTAK -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-geo-alt me-2 text-primary"></i>2. Alamat & Informasi Kontak</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="alamat" class="form-label small fw-bold">Alamat Lengkap Sekolah <span class="text-danger">*</span></label>
                                <textarea id="alamat" name="alamat" class="form-control field-input" rows="3" placeholder="Alamat lengkap sekolah" disabled required>{{ old('alamat', $pengaturan?->alamat ?? 'Jl. Raya Linggapura No. 46, RT 03/RW 03 - Tonjong - Brebes') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="kota" class="form-label small fw-bold">Kota / Kabupaten Penerbitan Surat <span class="text-danger">*</span></label>
                                <input type="text" id="kota" name="kota" class="form-control field-input" value="{{ old('kota', $pengaturan?->kota ?? 'Brebes') }}" placeholder="Contoh: Brebes atau Tonjong" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label for="nomor_hp" class="form-label small fw-bold">Nomor HP / Telepon Sekolah</label>
                                <input type="text" id="nomor_hp" name="nomor_hp" class="form-control field-input" value="{{ old('nomor_hp', old('telepon', $pengaturan?->nomor_hp ?? $pengaturan?->telepon ?? '(+62) 851-850-333-77')) }}" placeholder="Contoh: 08xxxxxxxxxx" disabled>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold">Email Resmi Sekolah</label>
                                <input type="email" id="email" name="email" class="form-control field-input" value="{{ old('email', $pengaturan?->email ?? 'smpmuhitonjong@gmail.com') }}" placeholder="Contoh: sekolah@email.com" disabled>
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label small fw-bold">Website Resmi Sekolah</label>
                                <input type="text" id="website" name="website" class="form-control field-input" value="{{ old('website', $pengaturan?->website ?? 'smpmuhtonjong.sch.id') }}" placeholder="Contoh: https://..." disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: PEJABAT PENANDATANGAN -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-person-badge me-2 text-warning"></i>3. Pejabat Penandatangan Surat & Laporan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_kepala_sekolah" class="form-label small fw-bold">Nama Kepala Sekolah <span class="text-danger">*</span></label>
                                <input type="text" id="nama_kepala_sekolah" name="nama_kepala_sekolah" class="form-control field-input" value="{{ old('nama_kepala_sekolah', $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.') }}" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label for="nip_kepala_sekolah" class="form-label small fw-bold">NIP / NBM Kepala Sekolah</label>
                                <input type="text" id="nip_kepala_sekolah" name="nip_kepala_sekolah" class="form-control field-input" value="{{ old('nip_kepala_sekolah', $pengaturan?->nip_kepala_sekolah ?? '-') }}" placeholder="Isi '-' jika tidak ada NIP" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: LOGO KOP SURAT -->
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light border">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-image me-2"></i>Logo Resmi Kop Surat</h6>
                        
                        <div class="row align-items-center g-3 mb-3">
                            <div class="col-auto">
                                <div class="bg-white border rounded-3 p-2 text-center" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                    @if ($pengaturan?->logo && file_exists(public_path('storage/' . $pengaturan->logo)))
                                        <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo Sekolah" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                    @elseif (file_exists(public_path('images/logo-sekolah.png')))
                                        <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                    @else
                                        <span class="text-muted small">Tidak Ada Logo</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <p class="small text-muted mb-1">
                                    <strong class="text-dark">Upload logo jika ingin mengganti logo resmi sekolah pada Kop Surat.</strong><br>
                                    Format yang disarankan: PNG atau JPG transparan.
                                </p>
                            </div>
                        </div>

                        <input type="file" id="logo" name="logo" class="form-control field-input" accept=".png,.jpg,.jpeg" disabled>
                    </div>
                </div>

                <!-- FOOTER TOMBOL AKSI -->
                <div class="d-flex justify-content-end gap-2 mb-5 pt-2">
                    <button type="button" id="btnEdit" class="btn btn-warning text-white fw-bold px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data
                    </button>
                    <button type="submit" id="btnSimpan" class="btn btn-success fw-bold px-4" style="display: none;">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Identitas Sekolah
                    </button>
                    <button type="button" id="btnBatal" class="btn btn-outline-secondary fw-semibold px-4" style="display: none;">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnEdit = document.getElementById('btnEdit');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnBatal = document.getElementById('btnBatal');
    const inputs = document.querySelectorAll('.field-input');

    btnEdit.addEventListener('click', function () {
        inputs.forEach(input => input.removeAttribute('disabled'));
        btnEdit.style.display = 'none';
        btnSimpan.style.display = 'inline-block';
        btnBatal.style.display = 'inline-block';
    });

    btnBatal.addEventListener('click', function () {
        inputs.forEach(input => input.setAttribute('disabled', 'disabled'));
        btnEdit.style.display = 'inline-block';
        btnSimpan.style.display = 'none';
        btnBatal.style.display = 'none';
    });
});
</script>
@endpush