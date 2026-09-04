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
                <!-- Tombol Tambah Manual -->
                <button type="button" class="btn btn-primary btn-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Pegawai</span>
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            
            <!-- ALERT SUCCESS -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- TABEL DATA PEGAWAI (LENGKAP 13 FIELD) -->
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
                            <th style="width: 110px;" class="text-center">Aksi</th>
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
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT DATA PEGAWAI (13 FIELD LENGKAP) -->
                                    <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Data Pegawai / Guru</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('pegawai.update', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body text-start">
                                                        <div class="row g-3">
                                                            <!-- Row 1: Nama & NIK -->
                                                            <div class="col-md-8">
                                                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">NIK</label>
                                                                <input type="text" name="nik" class="form-control" value="{{ $p->nik }}">
                                                            </div>

                                                            <!-- Row 2: NUPTK, NIP, NRG, NBM -->
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">NUPTK</label>
                                                                <input type="text" name="nuptk" class="form-control" value="{{ $p->nuptk }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">NIP</label>
                                                                <input type="text" name="nip" class="form-control" value="{{ $p->nip }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">NRG</label>
                                                                <input type="text" name="nrg" class="form-control" value="{{ $p->nrg }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">NBM</label>
                                                                <input type="text" name="nbm" class="form-control" value="{{ $p->nbm }}">
                                                            </div>

                                                            <!-- Row 3: TTL, JK, Pendidikan, TMT -->
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                                                                <input type="text" name="ttl" class="form-control" value="{{ $p->ttl }}" placeholder="Brebes, 12 Mei 1985">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                                                <select name="jenis_kelamin" class="form-select">
                                                                    <option value="Perempuan" {{ ($p->jenis_kelamin == 'Perempuan' || $p->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
                                                                    <option value="Laki-Laki" {{ ($p->jenis_kelamin == 'Laki-Laki' || $p->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-Laki</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                                                                <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $p->pendidikan_terakhir }}" placeholder="S.1 Pendidikan">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">TMT / Tgl Mulai Tugas</label>
                                                                <input type="text" name="tmt" class="form-control" value="{{ $p->tmt ?? $p->tgl_mulai_tugas }}" placeholder="01-07-2015">
                                                            </div>

                                                            <!-- Row 4: Jabatan & Satminkal -->
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Jabatan</label>
                                                                <input type="text" name="jabatan" class="form-control" value="{{ $p->jabatan }}" placeholder="Guru / Waka">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Satminkal / Induk</label>
                                                                <input type="text" name="satminkal" class="form-control" value="{{ $p->satminkal ?? 'SMP Muhammadiyah Tonjong (Induk)' }}">
                                                            </div>

                                                            <!-- Row 5: Alamat -->
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                                                <textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data pegawai/guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA PEGAWAI (13 FIELD LENGKAP) -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pegawai / Guru Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama beserta gelar" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NIK</label>
                            <input type="text" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">NUPTK</label>
                            <input type="text" name="nuptk" class="form-control" placeholder="NUPTK">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control" placeholder="NIP">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">NRG</label>
                            <input type="text" name="nrg" class="form-control" placeholder="NRG">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">NBM</label>
                            <input type="text" name="nbm" class="form-control" placeholder="NBM">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                            <input type="text" name="ttl" class="form-control" placeholder="Brebes, 12 Mei 1985">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="Perempuan">Perempuan</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="S.1 Pendidikan">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">TMT / Tgl Mulai Tugas</label>
                            <input type="text" name="tmt" class="form-control" placeholder="01-07-2015">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="Guru" placeholder="Guru / Waka">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satminkal / Induk</label>
                            <input type="text" name="satminkal" class="form-control" value="SMP Muhammadiyah Tonjong (Induk)">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat domisili"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
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