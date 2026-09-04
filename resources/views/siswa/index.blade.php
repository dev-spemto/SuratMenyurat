@extends('layouts.app')

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
                <!-- Tombol Tambah Manual -->
                <button type="button" class="btn btn-primary btn-sm fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Siswa</span>
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            
            <!-- TABEL DATA SISWA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>NIS / NISN / NIK</th>
                            <th>Nama Siswa</th>
                            <th>L/P</th>
                            <th>Kelas</th>
                            <th>TTL</th>
                            <th>Nama Orang Tua / Alamat</th>
                            <th style="width: 110px;" class="text-center">Aksi</th>
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
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $siswa->jenis_kelamin }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $siswa->kelas }}</span>
                                </td>
                                <td><small>{{ $siswa->ttl ?? '-' }}</small></td>
                                <td>
                                    <div class="small">
                                        <div><strong class="text-dark">{{ $siswa->nama_orang_tua ?? '-' }}</strong></div>
                                        <div class="text-muted">{{ $siswa->alamat ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT SISWA -->
                                    <div class="modal fade" id="modalEditSiswa{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Data Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-8">
                                                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">NIK</label>
                                                                <input type="text" name="nik" class="form-control" value="{{ $siswa->nik }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">NIS</label>
                                                                <input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">NISN</label>
                                                                <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                                                                <input type="text" name="kelas" class="form-control" value="{{ $siswa->kelas }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                                                                <input type="text" name="ttl" class="form-control" value="{{ $siswa->ttl }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                                                <select name="jenis_kelamin" class="form-select">
                                                                    <option value="Laki-Laki" {{ ($siswa->jenis_kelamin == 'Laki-Laki' || $siswa->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-Laki</option>
                                                                    <option value="Perempuan" {{ ($siswa->jenis_kelamin == 'Perempuan' || $siswa->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Nama Orang Tua / Wali</label>
                                                                <input type="text" name="nama_orang_tua" class="form-control" value="{{ $siswa->nama_orang_tua }}">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                                                <textarea name="alamat" class="form-control" rows="2">{{ $siswa->alamat }}</textarea>
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
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data siswa. Silakan tambah manual atau import data Excel.</td>
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

<!-- MODAL TAMBAH SISWA MANUAL -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama siswa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NIK</label>
                            <input type="text" name="nik" class="form-control" placeholder="NIK siswa">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NIS</label>
                            <input type="text" name="nis" class="form-control" placeholder="NIS">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="NISN">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: VII A" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                            <input type="text" name="ttl" class="form-control" placeholder="Brebes, 12 Mei 2011">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_orang_tua" class="form-control" placeholder="Nama ayah/ibu">
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
                        <code>nis</code>, <code>nisn</code>, <code>nik</code>, <code>nama</code>, <code>ttl</code>, <code>jenis_kelamin</code>, <code>kelas</code>, <code>nama_orang_tua</code>, <code>alamat</code>
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