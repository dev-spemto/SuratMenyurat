@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <span>
                        @if($jenisSurat === 'sppd') Buat SPPD & Surat Tugas Baru
                        @elseif($jenisSurat === 'aktif_belajar') Buat Surat Keterangan Aktif Belajar
                        @elseif($jenisSurat === 'aktif_mengajar') Buat Surat Keterangan Aktif Mengajar
                        @else Buat Surat Custom Baru
                        @endif
                    </span>
                </h5>
                <a href="{{ route('surat.index', ['jenis' => $jenisSurat]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <!-- PESAN ERROR VALIDASI -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <strong>Terjadi Kesalahan Input:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('surat.store', ['jenis' => $jenisSurat]) }}" method="POST">
                    @csrf
                    
                    <!-- Sembunyikan Jenis Surat (Otomatis Sesuai Kategori) -->
                    <input type="hidden" name="jenis_surat" value="{{ $jenisSurat }}">

                    <!-- FORM UTAMA (COMMON FIELDS) -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat') }}" placeholder="Contoh: 128/IV.4.AU/F/KET/III/2026" required>
                            @error('nomor_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Jenis Surat</label>
                            <input type="text" class="form-control bg-light text-muted fw-bold" value="{{ strtoupper(str_replace('_', ' ', $jenisSurat)) }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Tanggal Surat <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_surat" class="form-control" value="{{ old('tgl_surat', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Lampiran</label>
                            <input type="text" name="lampiran" class="form-control" value="{{ old('lampiran', '-') }}" placeholder="-">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Perihal <span class="text-danger">*</span></label>
                            @if($jenisSurat === 'aktif_mengajar')
                                <!-- Perihal Dikunci Khusus Aktif Mengajar -->
                                <input type="text" name="perihal" class="form-control bg-light text-muted fw-bold" value="Surat Keterangan Aktif Mengajar" readonly required>
                            @elseif($jenisSurat === 'aktif_belajar')
                                <!-- Perihal Dikunci Khusus Aktif Belajar -->
                                <input type="text" name="perihal" class="form-control bg-light text-muted fw-bold" value="Surat Keterangan Aktif Belajar" readonly required>
                            @else
                                <input type="text" name="perihal" class="form-control" value="{{ old('perihal') }}" placeholder="Contoh: Perjalanan Dinas Workshop / MGMP" required>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- DETAIL FORM KHUSUS SPPD -->
                    @if($jenisSurat === 'sppd')
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>Detail SPPD & Surat Tugas</span>
                        </h6>

                        <!-- Input Hidden Tujuan Penerima (Auto Fill dari Personil Pertama) -->
                        <input type="hidden" name="tujuan_penerima" id="sppd_tujuan_penerima">

                        <!-- Multi Personil -->
                        <div class="bg-light p-3 rounded-3 border mb-3">
                            <label class="form-label fw-semibold text-dark mb-2">Pegawai yang Diperintahkan (Multi-Personil)</label>
                            
                            <div id="personil-container" class="vstack gap-2">
                                <div class="row g-2 align-items-center personil-row">
                                    <div class="col-md-6">
                                        <select name="pegawai_ids[]" class="form-select select-pegawai" required>
                                            <option value="">-- Pilih Pegawai / Guru --</option>
                                            @foreach($pegawais as $p)
                                                @php
                                                    $nuptk = $p->nuptk_display ?? $p->nuptk_nip ?? $p->nuptk ?? $p->nip ?? '-';
                                                    $namaClean = preg_replace('/\.{2,}/', '.', trim($p->nama));
                                                @endphp
                                                <option value="{{ $p->id }}" data-nama="{{ $namaClean }}" data-jabatan="{{ $p->jabatan }}">
                                                    {{ $namaClean }} (NUPTK: {{ $nuptk }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="pegawai_jabatans[]" class="form-select select-jabatan">
                                            <option value="">-- Pilih / Sesuaikan Jabatan --</option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Waka Kurikulum">Waka Kurikulum</option>
                                            <option value="Waka Sarpras">Waka Sarpras</option>
                                            <option value="Waka Kesiswaan">Waka Kesiswaan</option>
                                            <option value="Waka Humas">Waka Humas</option>
                                            <option value="Guru BK">Guru BK</option>
                                            <option value="Guru">Guru</option>
                                            <option value="Operator Sekolah">Operator Sekolah</option>
                                            <option value="Ka. Perpus">Ka. Perpus</option>
                                            <option value="Ka. Lab IPA">Ka. Lab IPA</option>
                                            <option value="Ka. Labkom">Ka. Labkom</option>
                                            <option value="Bendahara Sekolah">Bendahara Sekolah</option>
                                            <option value="Tenaga Administrasi Sekolah">Tenaga Administrasi Sekolah</option>
                                            <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                                            <option value="Pembina Ekskul">Pembina Ekskul</option>
                                            <option value="Komite Sekolah">Komite Sekolah</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger w-100 btn-remove-personil" disabled>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="btn-add-personil" class="btn btn-sm btn-outline-primary mt-3 d-inline-flex align-items-center gap-1">
                                <i class="bi bi-plus-lg"></i>
                                <span>Tambah Orang</span>
                            </button>
                        </div>

                        <!-- Tempat & Maksud -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Tempat Tujuan <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_tujuan" class="form-control" value="{{ old('tempat_tujuan') }}" placeholder="Contoh: SMP Negeri 1 Bumiayu" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Maksud Perjalanan Dinas <span class="text-danger">*</span></label>
                                <textarea name="maksud_dinas" class="form-control" rows="2" placeholder="Contoh: MGMP PAI: Pelatihan Teknik Penyelenggaraan Jenazah" required>{{ old('maksud_dinas') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Berangkat <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_berangkat" class="form-control" value="{{ old('tgl_berangkat', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Kembali <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_kembali" class="form-control" value="{{ old('tgl_kembali', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Keterangan Tambahan <span class="text-muted font-normal">(Opsional)</span></label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Materi: Kurikulum Merdeka / Peserta wajib membawa laptop">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- DETAIL FORM KHUSUS SURAT KETERANGAN AKTIF BELAJAR -->
                    @if($jenisSurat === 'aktif_belajar')
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span>Detail Data Siswa / Peserta Didik</span>
                        </h6>

                        <!-- Dropdown Pilih Siswa -->
                        <div class="bg-light p-3 rounded-3 border mb-3">
                            <label class="form-label fw-semibold text-dark mb-1">Pilih Siswa / Peserta Didik <span class="text-danger">*</span></label>
                            <select id="select-siswa" name="siswa_id" class="form-select border-success" required>
                                <option value="">-- Pilih Nama Siswa / NISN --</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('siswa_id') == $s->id ? 'selected' : '' }}
                                        data-nama="{{ $s->nama }}"
                                        data-nis="{{ $s->nis ?? '' }}"
                                        data-nisn="{{ $s->nisn ?? '' }}"
                                        data-nik="{{ $s->nik ?? '' }}"
                                        data-ttl="{{ $s->ttl ?? '' }}"
                                        data-kelas="{{ $s->kelas ?? '' }}"
                                        data-jk="{{ $s->jenis_kelamin ?? 'Laki-Laki' }}"
                                        data-ortu="{{ $s->nama_orang_tua ?? $s->nama_ayah ?? $s->nama_ibu ?? '' }}"
                                        data-alamat="{{ $s->alamat ?? '' }}">
                                        {{ $s->nama }} - {{ $s->kelas ?? '' }} (NISN: {{ $s->nisn ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keperluan Surat -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Keperluan Surat <span class="text-danger">*</span></label>
                            <input type="text" name="payload[keperluan]" class="form-control border-primary" value="{{ old('payload.keperluan', old('keperluan')) }}" placeholder="Contoh: Persyaratan Pengajuan Beasiswa / PIP" required>
                        </div>

                        <!-- HIDDEN FIELDS: Data Otomatis Dikirim ke Controller Saat Siswa Dipilih -->
                        <input type="hidden" name="payload[nama_siswa]" id="siswa_nama">
                        <input type="hidden" name="payload[nis]" id="siswa_nis">
                        <input type="hidden" name="payload[nisn]" id="siswa_nisn">
                        <input type="hidden" name="payload[nik]" id="siswa_nik">
                        <input type="hidden" name="payload[ttl]" id="siswa_ttl">
                        <input type="hidden" name="payload[kelas]" id="siswa_kelas">
                        <input type="hidden" name="payload[jenis_kelamin]" id="siswa_jk">
                        <input type="hidden" name="payload[nama_orang_tua]" id="siswa_ortu">
                        <input type="hidden" name="payload[alamat]" id="siswa_alamat">
                    </div>
                    @endif

                    <!-- DETAIL FORM KHUSUS SURAT KETERANGAN AKTIF MENGAJAR -->
                    @if($jenisSurat === 'aktif_mengajar')
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-workspace"></i>
                            <span>Detail Data Guru / Pendidik</span>
                        </h6>

                        <!-- Dropdown Nama Guru -->
                        <div class="bg-light p-3 rounded-3 border mb-3">
                            <label class="form-label fw-semibold text-dark mb-1">Pilih Guru / Pegawai <span class="text-danger">*</span></label>
                            <select id="select-guru" name="pegawai_id" class="form-select border-primary" required>
                                <option value="">-- Pilih Guru / Pegawai --</option>
                                @foreach($pegawais as $p)
                                    @php $namaClean = preg_replace('/\.{2,}/', '.', trim($p->nama)); @endphp
                                    <option value="{{ $p->id }}"
                                        {{ old('pegawai_id') == $p->id ? 'selected' : '' }}
                                        data-nama="{{ $namaClean }}"
                                        data-ttl="{{ $p->ttl ?? '' }}"
                                        data-nuptk="{{ $p->nuptk ?? $p->nuptk_nip ?? $p->nip ?? '' }}"
                                        data-nrg="{{ $p->nrg ?? '' }}"
                                        data-nbm="{{ $p->nbm ?? '' }}"
                                        data-nik="{{ $p->nik ?? '' }}"
                                        data-jk="{{ $p->jenis_kelamin ?? 'Perempuan' }}"
                                        data-pendidikan="{{ $p->pendidikan_terakhir ?? '' }}"
                                        data-tmt="{{ $p->tmt ?? $p->tgl_mulai_tugas ?? '' }}"
                                        data-alamat="{{ $p->alamat ?? '' }}"
                                        data-satminkal="{{ $p->satminkal ?? 'SMP Muhammadiyah Tonjong (Induk)' }}">
                                        {{ $namaClean }} (NUPTK/NIP: {{ $p->nuptk ?? $p->nip ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- HIDDEN FIELDS: Data Otomatis Dikirim ke Controller Saat Form Submit -->
                        <input type="hidden" name="nama_guru" id="nama_guru">
                        <input type="hidden" name="ttl" id="ttl">
                        <input type="hidden" name="nuptk" id="nuptk">
                        <input type="hidden" name="nrg" id="nrg">
                        <input type="hidden" name="nbm" id="nbm">
                        <input type="hidden" name="nik" id="nik">
                        <input type="hidden" name="jenis_kelamin" id="jenis_kelamin">
                        <input type="hidden" name="pendidikan_terakhir" id="pendidikan_terakhir">
                        <input type="hidden" name="tgl_mulai_tugas" id="tgl_mulai_tugas">
                        <input type="hidden" name="alamat_guru" id="alamat_guru">
                        <input type="hidden" name="satminkal" id="satminkal" value="SMP Muhammadiyah Tonjong (Induk)">
                        <input type="hidden" name="jabatan_status" id="jabatan_status" value="Guru">
                    </div>
                    @endif

                    <!-- DETAIL FORM KHUSUS SURAT CUSTOM -->
                    @if($jenisSurat === 'custom')
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Detail Surat Custom / Lainnya</span>
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tujuan / Penerima Surat <span class="text-danger">*</span></label>
                                <input type="text" name="tujuan_penerima" class="form-control" value="{{ old('tujuan_penerima') }}" placeholder="Contoh: Pimpinan Daerah Muhammadiyah Brebes" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Waktu / Tempat Acara <span class="text-muted font-normal">(Opsional)</span></label>
                                <input type="text" name="waktu_acara" class="form-control" value="{{ old('waktu_acara') }}" placeholder="Contoh: Sabtu, 10 Oktober 2026 / Aula Sekolah">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Isi Surat <span class="text-danger">*</span></label>
                                <textarea name="isi_surat" class="form-control" rows="6" placeholder="Tuliskan isi ringkas atau narasi lengkap surat di sini..." required>{{ old('isi_surat') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('surat.index', ['jenis' => $jenisSurat]) }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-primary fw-semibold px-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-floppy-fill"></i>
                            <span>Simpan & Generate Surat</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- SCRIPT KHUSUS SPPD REPEATER ---
        const container = document.getElementById('personil-container');
        const btnAdd = document.getElementById('btn-add-personil');
        const hiddenTujuanPenerima = document.getElementById('sppd_tujuan_penerima');

        if (container) {
            function updateSppdTujuanPenerima() {
                const selects = container.querySelectorAll('.select-pegawai');
                const names = [];
                selects.forEach(sel => {
                    if (sel.value) {
                        const opt = sel.options[sel.selectedIndex];
                        const nama = opt.getAttribute('data-nama');
                        if (nama) names.push(nama);
                    }
                });
                if (hiddenTujuanPenerima) {
                    hiddenTujuanPenerima.value = names.join(', ');
                }
            }

            container.addEventListener('change', function (e) {
                if (e.target.classList.contains('select-pegawai')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const jabatan = selectedOption.getAttribute('data-jabatan');
                    const row = e.target.closest('.personil-row');
                    const selectJabatan = row.querySelector('.select-jabatan');

                    if (jabatan && selectJabatan) {
                        selectJabatan.value = jabatan;
                    }
                    updateSppdTujuanPenerima();
                }
            });

            if (btnAdd) {
                btnAdd.addEventListener('click', function () {
                    const firstRow = container.querySelector('.personil-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.querySelector('.select-pegawai').value = '';
                    newRow.querySelector('.select-jabatan').value = '';

                    const btnRemove = newRow.querySelector('.btn-remove-personil');
                    btnRemove.removeAttribute('disabled');

                    container.appendChild(newRow);
                    updateRemoveButtons();
                });
            }

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-personil')) {
                    const rows = container.querySelectorAll('.personil-row');
                    if (rows.length > 1) {
                        e.target.closest('.personil-row').remove();
                        updateRemoveButtons();
                        updateSppdTujuanPenerima();
                    }
                }
            });

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.personil-row');
                rows.forEach((row) => {
                    const btnRemove = row.querySelector('.btn-remove-personil');
                    if (rows.length === 1) {
                        btnRemove.setAttribute('disabled', 'disabled');
                    } else {
                        btnRemove.removeAttribute('disabled');
                    }
                });
            }

            // Inisialisasi awal saat pertama kali load
            updateSppdTujuanPenerima();
        }

        // --- SCRIPT KHUSUS SURAT AKTIF BELAJAR ---
        const selectSiswa = document.getElementById('select-siswa');
        if (selectSiswa) {
            function updateSiswaHiddenFields() {
                const selectedOption = selectSiswa.options[selectSiswa.selectedIndex];
                if (selectSiswa.value) {
                    document.getElementById('siswa_nama').value   = selectedOption.getAttribute('data-nama') || '';
                    document.getElementById('siswa_nis').value    = selectedOption.getAttribute('data-nis') || '';
                    document.getElementById('siswa_nisn').value   = selectedOption.getAttribute('data-nisn') || '';
                    document.getElementById('siswa_nik').value    = selectedOption.getAttribute('data-nik') || '';
                    document.getElementById('siswa_ttl').value    = selectedOption.getAttribute('data-ttl') || '';
                    document.getElementById('siswa_kelas').value  = selectedOption.getAttribute('data-kelas') || '';
                    document.getElementById('siswa_jk').value     = selectedOption.getAttribute('data-jk') || '';
                    document.getElementById('siswa_ortu').value   = selectedOption.getAttribute('data-ortu') || '';
                    document.getElementById('siswa_alamat').value = selectedOption.getAttribute('data-alamat') || '';
                } else {
                    document.getElementById('siswa_nama').value   = '';
                    document.getElementById('siswa_nis').value    = '';
                    document.getElementById('siswa_nisn').value   = '';
                    document.getElementById('siswa_nik').value    = '';
                    document.getElementById('siswa_ttl').value    = '';
                    document.getElementById('siswa_kelas').value  = '';
                    document.getElementById('siswa_jk').value     = '';
                    document.getElementById('siswa_ortu').value   = '';
                    document.getElementById('siswa_alamat').value = '';
                }
            }
            selectSiswa.addEventListener('change', updateSiswaHiddenFields);
            if(selectSiswa.value) updateSiswaHiddenFields();
        }

        // --- SCRIPT KHUSUS SURAT AKTIF MENGAJAR ---
        const selectGuru = document.getElementById('select-guru');
        if (selectGuru) {
            function updateGuruHiddenFields() {
                const selectedOption = selectGuru.options[selectGuru.selectedIndex];
                if (selectGuru.value) {
                    document.getElementById('nama_guru').value = selectedOption.getAttribute('data-nama') || '';
                    document.getElementById('ttl').value = selectedOption.getAttribute('data-ttl') || '';
                    document.getElementById('nuptk').value = selectedOption.getAttribute('data-nuptk') || '';
                    document.getElementById('nrg').value = selectedOption.getAttribute('data-nrg') || '';
                    document.getElementById('nbm').value = selectedOption.getAttribute('data-nbm') || '';
                    document.getElementById('nik').value = selectedOption.getAttribute('data-nik') || '';
                    document.getElementById('jenis_kelamin').value = selectedOption.getAttribute('data-jk') || 'Perempuan';
                    document.getElementById('pendidikan_terakhir').value = selectedOption.getAttribute('data-pendidikan') || '';
                    document.getElementById('tgl_mulai_tugas').value = selectedOption.getAttribute('data-tmt') || '';
                    document.getElementById('alamat_guru').value = selectedOption.getAttribute('data-alamat') || '';
                    document.getElementById('satminkal').value = selectedOption.getAttribute('data-satminkal') || 'SMP Muhammadiyah Tonjong (Induk)';
                    document.getElementById('jabatan_status').value = 'Guru';
                } else {
                    document.getElementById('nama_guru').value = '';
                    document.getElementById('ttl').value = '';
                    document.getElementById('nuptk').value = '';
                    document.getElementById('nrg').value = '';
                    document.getElementById('nbm').value = '';
                    document.getElementById('nik').value = '';
                    document.getElementById('jenis_kelamin').value = '';
                    document.getElementById('pendidikan_terakhir').value = '';
                    document.getElementById('tgl_mulai_tugas').value = '';
                    document.getElementById('alamat_guru').value = '';
                    document.getElementById('satminkal').value = 'SMP Muhammadiyah Tonjong (Induk)';
                    document.getElementById('jabatan_status').value = 'Guru';
                }
            }
            selectGuru.addEventListener('change', updateGuruHiddenFields);
            if(selectGuru.value) updateGuruHiddenFields();
        }
    });
</script>
@endpush