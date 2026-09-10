@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="m-0 font-weight-bold text-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Surat ({{ strtoupper(str_replace('_', ' ', $surat->jenis_surat)) }})</span>
            </h5>
            <a href="{{ route('surat.index', ['jenis' => $surat->jenis_surat]) }}" class="btn btn-outline-secondary btn-sm fw-semibold">
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

            <form action="{{ route('surat.update', $surat->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php $p = $surat->payload_detail ?? []; @endphp

                <!-- KELOMPOK 1: INFORMASI UMUM SURAT -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_surat" class="form-control" value="{{ old('tgl_surat', $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Lampiran</label>
                        <input type="text" name="lampiran" class="form-control" value="{{ old('lampiran', $surat->lampiran ?? '-') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Perihal <span class="text-danger">*</span></label>
                        @if($surat->jenis_surat === 'aktif_mengajar')
                            <input type="text" name="perihal" class="form-control bg-light text-muted fw-bold" value="Surat Keterangan Aktif Mengajar" readonly required>
                        @elseif($surat->jenis_surat === 'aktif_belajar')
                            <input type="text" name="perihal" class="form-control bg-light text-muted fw-bold" value="Surat Keterangan Aktif Belajar" readonly required>
                        @else
                            <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $surat->perihal) }}" required>
                        @endif
                    </div>
                </div>

                <!-- KELOMPOK 2: DOKUMEN SPPD -->
                @if($surat->jenis_surat === 'sppd')
                    <hr class="my-4">
                    <h5 class="fw-bold text-dark mb-3">Detail Perjalanan Dinas (SPPD)</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Maksud Perjalanan Dinas <span class="text-danger">*</span></label>
                            <input type="text" name="maksud_dinas" class="form-control" value="{{ old('maksud_dinas', $p['maksud_dinas'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Tujuan <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_tujuan" class="form-control" value="{{ old('tempat_tujuan', $p['tempat_tujuan'] ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Berangkat <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_berangkat" class="form-control" value="{{ old('tgl_berangkat', $p['tgl_berangkat'] ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_kembali" class="form-control" value="{{ old('tgl_kembali', $p['tgl_kembali'] ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Transportasi</label>
                            <input type="text" name="transportasi" class="form-control" value="{{ old('transportasi', $p['transportasi'] ?? 'Pribadi') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Keterangan Opsional</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $p['keterangan'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- DROPDOWN NAMA & JABATAN DINAMIS -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark m-0">Pilih Pegawai Yang Ditugaskan:</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" id="btn-add-pegawai">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Pegawai
                        </button>
                    </div>

                    @php
                        $savedPegawais = $p['pegawai_list'] ?? [];
                        
                        $daftarJabatan = [
                            'Kepala Sekolah',
                            'Waka Kurikulum',
                            'Waka Sarpras',
                            'Waka Kesiswaan',
                            'Waka Humas',
                            'Guru BK',
                            'Guru',
                            'Operator Sekolah',
                            'Ka. Perpus',
                            'Ka. Lab IPA',
                            'Ka. Labkom',
                            'Bendahara Sekolah',
                            'Tenaga Administrasi Sekolah',
                            'Tenaga Kependidikan',
                            'Pembina Ekskul',
                            'Komite Sekolah',
                        ];
                    @endphp

                    <div id="pegawai-container" class="d-flex flex-column gap-2 mb-4">
                        @forelse($savedPegawais as $index => $savedPeg)
                            <div class="row g-2 align-items-center pegawai-row">
                                <div class="col-md-6">
                                    <select name="pegawai_ids[]" class="form-select pegawai-select" required>
                                        <option value="">-- Pilih Nama Pegawai / NUPTK --</option>
                                        @foreach($pegawais as $peg)
                                            @php
                                                $nuptkNip = $peg->nip ?? $peg->nuptk_nip ?? $peg->nuptk ?? $peg->nbm ?? '-';
                                                $isSaved = ($peg->id == ($savedPeg['id'] ?? null) || $peg->nama == ($savedPeg['nama'] ?? ''));
                                            @endphp
                                            <option value="{{ $peg->id }}" data-jabatan="{{ $peg->jabatan }}" {{ $isSaved ? 'selected' : '' }}>
                                                {{ $peg->nama }} (NUPTK/NIP: {{ $nuptkNip }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="pegawai_jabatans[]" class="form-select jabatan-select" required>
                                        <option value="">-- Pilih Jabatan Tugas --</option>
                                        @foreach($daftarJabatan as $jab)
                                            <option value="{{ $jab }}" {{ strcasecmp($savedPeg['jabatan'] ?? '', $jab) === 0 ? 'selected' : '' }}>
                                                {{ $jab }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-remove-pegawai w-100" title="Hapus Baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="row g-2 align-items-center pegawai-row">
                                <div class="col-md-6">
                                    <select name="pegawai_ids[]" class="form-select pegawai-select" required>
                                        <option value="">-- Pilih Nama Pegawai / NUPTK --</option>
                                        @foreach($pegawais as $peg)
                                            @php $nuptkNip = $peg->nip ?? $peg->nuptk_nip ?? $peg->nuptk ?? $peg->nbm ?? '-'; @endphp
                                            <option value="{{ $peg->id }}" data-jabatan="{{ $peg->jabatan }}">
                                                {{ $peg->nama }} (NUPTK/NIP: {{ $nuptkNip }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="pegawai_jabatans[]" class="form-select jabatan-select" required>
                                        <option value="">-- Pilih Jabatan Tugas --</option>
                                        @foreach($daftarJabatan as $jab)
                                            <option value="{{ $jab }}">{{ $jab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-remove-pegawai w-100" title="Hapus Baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                @endif

                <!-- KELOMPOK 3: AKTIF BELAJAR -->
                @if($surat->jenis_surat === 'aktif_belajar')
                    <hr class="my-4">
                    <h5 class="fw-bold text-success mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>Detail Data Siswa / Peserta Didik</span>
                    </h5>

                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <label class="form-label fw-semibold text-dark mb-1">Pilih Siswa / Peserta Didik <span class="text-danger">*</span></label>
                        <select id="select-siswa-edit" class="form-select border-success" required>
                            <option value="">-- Pilih Nama Siswa / NISN --</option>
                            @foreach($siswas as $sis)
                                @php 
                                    $isSelected = (trim($p['nama_siswa'] ?? '') === trim($sis->nama));
                                @endphp
                                <option value="{{ $sis->id }}" 
                                    data-nama="{{ $sis->nama }}"
                                    data-nis="{{ $sis->nis ?? '' }}"
                                    data-nisn="{{ $sis->nisn ?? '' }}"
                                    data-nik="{{ $sis->nik ?? '' }}"
                                    data-ttl="{{ $sis->ttl ?? '' }}"
                                    data-kelas="{{ $sis->kelas ?? '' }}"
                                    data-jk="{{ $sis->jenis_kelamin ?? 'Laki-Laki' }}"
                                    data-ortu="{{ $sis->nama_orang_tua ?? $sis->nama_ayah ?? $sis->nama_ibu ?? '' }}"
                                    data-alamat="{{ $sis->alamat ?? '' }}"
                                    {{ $isSelected ? 'selected' : '' }}>
                                    {{ $sis->nama }} - {{ $sis->kelas ?? '' }} (NISN: {{ $sis->nisn ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-dark">Keperluan Surat <span class="text-danger">*</span></label>
                        <input type="text" name="payload[keperluan]" class="form-control border-primary" value="{{ old('payload.keperluan', $p['keperluan'] ?? '') }}" required placeholder="Contoh: Persyaratan Pengajuan Beasiswa / PIP">
                    </div>

                    <input type="hidden" name="payload[nama_siswa]" id="siswa_nama" value="{{ old('payload.nama_siswa', $p['nama_siswa'] ?? '') }}">
                    <input type="hidden" name="payload[nis]" id="siswa_nis" value="{{ old('payload.nis', $p['nis'] ?? '') }}">
                    <input type="hidden" name="payload[nisn]" id="siswa_nisn" value="{{ old('payload.nisn', $p['nisn'] ?? '') }}">
                    <input type="hidden" name="payload[nik]" id="siswa_nik" value="{{ old('payload.nik', $p['nik'] ?? '') }}">
                    <input type="hidden" name="payload[ttl]" id="siswa_ttl" value="{{ old('payload.ttl', $p['ttl'] ?? '') }}">
                    <input type="hidden" name="payload[kelas]" id="siswa_kelas" value="{{ old('payload.kelas', $p['kelas'] ?? '') }}">
                    <input type="hidden" name="payload[jenis_kelamin]" id="siswa_jk" value="{{ old('payload.jenis_kelamin', $p['jenis_kelamin'] ?? '') }}">
                    <input type="hidden" name="payload[nama_orang_tua]" id="siswa_ortu" value="{{ old('payload.nama_orang_tua', $p['nama_orang_tua'] ?? $p['nama_ayah'] ?? $p['nama_ibu'] ?? '') }}">
                    <input type="hidden" name="payload[alamat]" id="siswa_alamat" value="{{ old('payload.alamat', $p['alamat'] ?? '') }}">
                @endif

                <!-- KELOMPOK 4: AKTIF MENGAJAR -->
                @if($surat->jenis_surat === 'aktif_mengajar')
                    <hr class="my-4">
                    <h5 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-person-workspace"></i>
                        <span>Detail Data Guru / Pendidik</span>
                    </h5>

                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <label class="form-label fw-semibold text-dark mb-1">Pilih Guru / Pegawai <span class="text-danger">*</span></label>
                        <select id="select-guru-edit" class="form-select border-primary" required>
                            <option value="">-- Pilih Guru / Pegawai --</option>
                            @foreach($pegawais as $peg)
                                @php 
                                    $namaClean = preg_replace('/\.{2,}/', '.', trim($peg->nama)); 
                                    $isSelected = (trim($p['nama_guru'] ?? '') === $namaClean || trim($p['nama_guru'] ?? '') === trim($peg->nama));
                                @endphp
                                <option value="{{ $peg->id }}" 
                                    data-nama="{{ $namaClean }}"
                                    data-ttl="{{ $peg->ttl ?? '' }}"
                                    data-nuptk="{{ $peg->nuptk ?? $peg->nuptk_nip ?? $peg->nip ?? '' }}"
                                    data-nrg="{{ $peg->nrg ?? '' }}"
                                    data-nbm="{{ $peg->nbm ?? '' }}"
                                    data-nik="{{ $peg->nik ?? '' }}"
                                    data-jk="{{ $peg->jenis_kelamin ?? 'Perempuan' }}"
                                    data-pendidikan="{{ $peg->pendidikan_terakhir ?? '' }}"
                                    data-tmt="{{ $peg->tmt ?? $peg->tgl_mulai_tugas ?? '' }}"
                                    data-alamat="{{ $peg->alamat ?? '' }}"
                                    data-satminkal="{{ $peg->satminkal ?? 'SMP Muhammadiyah Tonjong (Induk)' }}"
                                    {{ $isSelected ? 'selected' : '' }}>
                                    {{ $namaClean }} (NUPTK/NIP: {{ $peg->nuptk ?? $peg->nip ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="nama_guru" id="nama_guru" value="{{ old('nama_guru', $p['nama_guru'] ?? '') }}">
                    <input type="hidden" name="ttl" id="ttl" value="{{ old('ttl', $p['ttl'] ?? '') }}">
                    <input type="hidden" name="nuptk" id="nuptk" value="{{ old('nuptk', $p['nuptk'] ?? '') }}">
                    <input type="hidden" name="nrg" id="nrg" value="{{ old('nrg', $p['nrg'] ?? '') }}">
                    <input type="hidden" name="nbm" id="nbm" value="{{ old('nbm', $p['nbm'] ?? '') }}">
                    <input type="hidden" name="nik" id="nik" value="{{ old('nik', $p['nik'] ?? '') }}">
                    <input type="hidden" name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin', $p['jenis_kelamin'] ?? 'Perempuan') }}">
                    <input type="hidden" name="pendidikan_terakhir" id="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $p['pendidikan_terakhir'] ?? '') }}">
                    <input type="hidden" name="tgl_mulai_tugas" id="tgl_mulai_tugas" value="{{ old('tgl_mulai_tugas', $p['tmt'] ?? '') }}">
                    <input type="hidden" name="alamat_guru" id="alamat_guru" value="{{ old('alamat_guru', $p['alamat'] ?? '') }}">
                    <input type="hidden" name="satminkal" id="satminkal" value="{{ old('satminkal', $p['satminkal'] ?? 'SMP Muhammadiyah Tonjong (Induk)') }}">
                    <input type="hidden" name="jabatan_status" id="jabatan_status" value="Guru">
                @endif

                <!-- KELOMPOK 5: UNDANGAN, PEMBERITAHUAN, PERMOHONAN -->
                @if(in_array($surat->jenis_surat, ['und', 'pbh', 'pmh']))
                    <hr class="my-4">
                    <h5 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-open-fill"></i>
                        <span>Detail Surat {{ strtoupper($surat->jenis_surat) }}</span>
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Kepada Yth. (Penerima) <span class="text-danger">*</span></label>
                            <input type="text" name="tujuan_penerima" class="form-control" value="{{ old('tujuan_penerima', $surat->tujuan) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Di (Tempat) <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_penerima" class="form-control" value="{{ old('tempat_penerima', $p['tempat_penerima'] ?? 'di - Tempat') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Salam Pembuka</label>
                            <input type="text" name="salam_pembuka" class="form-control" value="{{ old('salam_pembuka', $p['salam_pembuka'] ?? "Assalamu'alaikum Wr. Wb.") }}" placeholder="Kosongkan jika tidak ingin menampilkan salam pembuka">
                        </div>

                        <div class="col-12">
                            <label class="form-label font-weight-bold">Paragraf Pembuka</label>
                            <textarea name="paragraf_pembuka" class="form-control" rows="2">{{ old('paragraf_pembuka', $p['paragraf_pembuka'] ?? '') }}</textarea>
                        </div>

                        <!-- Detail Agenda Kegiatan -->
                        <div class="col-12 bg-light p-3 rounded-3 border">
                            <div class="fw-bold text-secondary mb-2">Informasi Waktu & Acara</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Hari, Tanggal</label>
                                    <input type="text" name="hari_tanggal" class="form-control" value="{{ old('hari_tanggal', $p['hari_tanggal'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Waktu / Jam</label>
                                    <input type="text" name="waktu_acara" class="form-control" value="{{ old('waktu_acara', $p['waktu_acara'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tempat Acara</label>
                                    <input type="text" name="tempat_acara" class="form-control" value="{{ old('tempat_acara', $p['tempat_acara'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Agenda / Acara</label>
                                    <input type="text" name="agenda_acara" class="form-control" value="{{ old('agenda_acara', $p['agenda_acara'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Keterangan Tambahan (Opsional)</label>
                                    <input type="text" name="keterangan_acara" class="form-control" value="{{ old('keterangan_acara', $p['keterangan_acara'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label font-weight-bold">Paragraf Penutup</label>
                            <textarea name="paragraf_penutup" class="form-control" rows="2">{{ old('paragraf_penutup', $p['paragraf_penutup'] ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Salam Penutup</label>
                            <input type="text" name="salam_penutup" class="form-control" value="{{ old('salam_penutup', $p['salam_penutup'] ?? "Wassalamu'alaikum Wr. Wb.") }}" placeholder="Kosongkan jika tidak ingin menampilkan salam penutup">
                        </div>

                        <!-- Tembusan Surat Dinamis -->
                        @php
                            $savedTembusan = $p['tembusan_list'] ?? $p['tembusan'] ?? [];
                            if (is_string($savedTembusan)) {
                                $savedTembusan = explode(',', $savedTembusan);
                            }
                            $tembusanOld = old('tembusan', $savedTembusan);
                            
                            // Menyaring string 'Pertinggal (Arsip)' atau nomor dari database
                            $cleanInputs = array_values(array_filter((array)$tembusanOld, function($item) {
                                $t = trim($item);
                                $tLower = strtolower($t);
                                return !empty($t) && $tLower !== 'pertinggal (arsip)' && $tLower !== '2. pertinggal (arsip)' && !str_contains($tLower, 'pertinggal');
                            }));
                        @endphp
                        <div class="col-12">
                            <label class="form-label font-weight-bold mb-1">Tembusan Surat</label>
                            
                            <div id="wrapper-tembusan" class="vstack gap-2">
                                @forelse($cleanInputs as $idx => $val)
                                    <div class="input-group item-tembusan">
                                        <span class="input-group-text label-nomor fw-bold">{{ $idx + 1 }}.</span>
                                        <input type="text" name="tembusan[]" class="form-control" value="{{ preg_replace('/^\d+\.\s*/', '', $val) }}" placeholder="Contoh: Majelis Dikdasmen PCM Tonjong">
                                        <button type="button" class="btn btn-outline-danger btn-remove-tembusan">Hapus</button>
                                    </div>
                                @empty
                                    <div class="input-group item-tembusan">
                                        <span class="input-group-text label-nomor fw-bold">1.</span>
                                        <input type="text" name="tembusan[]" class="form-control" value="Majelis Dikdasmen PCM Tonjong" placeholder="Contoh: Majelis Dikdasmen PCM Tonjong">
                                        <button type="button" class="btn btn-outline-danger btn-remove-tembusan" style="display:none;">Hapus</button>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" id="btn-add-tembusan" class="btn btn-sm btn-outline-primary mt-2 d-inline-flex align-items-center gap-1">
                                <i class="bi bi-plus-lg"></i>
                                <span>Tambah Tembusan</span>
                            </button>
                            <small class="text-muted d-block mt-1">*Sistem akan otomatis menambahkan "Pertinggal (Arsip)" di urutan paling akhir saat surat dicetak.</small>
                        </div>
                    </div>
                @endif

                <!-- KELOMPOK 6: SURAT CUSTOM -->
                @if($surat->jenis_surat === 'custom')
                    <hr class="my-4">
                    <h5 class="fw-bold text-dark mb-3">Detail Surat Custom</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tujuan / Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="tujuan_penerima" class="form-control" value="{{ old('tujuan_penerima', $surat->tujuan) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu / Tempat Acara</label>
                            <input type="text" name="waktu_acara" class="form-control" value="{{ old('waktu_acara', $p['waktu_acara'] ?? '') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Isi Surat <span class="text-danger">*</span></label>
                            <textarea name="isi_surat" class="form-control" rows="5" required>{{ old('isi_surat', $p['isi_surat'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endif

                <div class="mt-4 pt-2 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('surat.index', ['jenis' => $surat->jenis_surat]) }}" class="btn btn-light border fw-semibold">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- TEMBUSAN SURAT DINAMIS ---
        const wrapperTembusan = document.getElementById('wrapper-tembusan');
        const btnAddTembusan = document.getElementById('btn-add-tembusan');

        if (wrapperTembusan) {
            function updateNomorTembusan() {
                const items = wrapperTembusan.querySelectorAll('.item-tembusan');
                items.forEach((item, index) => {
                    const label = item.querySelector('.label-nomor');
                    if (label) label.textContent = (index + 1) + '.';
                    
                    const btnHapus = item.querySelector('.btn-remove-tembusan');
                    if (btnHapus) {
                        btnHapus.style.display = items.length > 1 ? 'block' : 'none';
                    }
                });
            }

            if (btnAddTembusan) {
                btnAddTembusan.addEventListener('click', function () {
                    const newItem = document.createElement('div');
                    newItem.className = 'input-group item-tembusan';
                    newItem.innerHTML = `
                        <span class="input-group-text label-nomor fw-bold">.</span>
                        <input type="text" name="tembusan[]" class="form-control" placeholder="Tembusan lainnya...">
                        <button type="button" class="btn btn-outline-danger btn-remove-tembusan">Hapus</button>
                    `;
                    wrapperTembusan.appendChild(newItem);
                    updateNomorTembusan();
                });
            }

            wrapperTembusan.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-tembusan')) {
                    const row = e.target.closest('.item-tembusan');
                    if (wrapperTembusan.querySelectorAll('.item-tembusan').length > 1) {
                        row.remove();
                        updateNomorTembusan();
                    }
                }
            });

            updateNomorTembusan();
        }

        // --- REPEATER SPPD ---
        const container = document.getElementById('pegawai-container');
        const btnAdd = document.getElementById('btn-add-pegawai');

        if (btnAdd && container) {
            btnAdd.addEventListener('click', function() {
                const firstRow = container.querySelector('.pegawai-row');
                if (!firstRow) return;

                const newRow = firstRow.cloneNode(true);
                newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                container.appendChild(newRow);
            });
        }

        if (container) {
            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-pegawai')) {
                    const rows = container.querySelectorAll('.pegawai-row');
                    if (rows.length > 1) {
                        e.target.closest('.pegawai-row').remove();
                    } else {
                        alert('Atur minimal 1 pegawai untuk penugasan SPPD.');
                    }
                }
            });

            container.addEventListener('change', function(e) {
                if (e.target.classList.contains('pegawai-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const defaultJabatan = selectedOption.getAttribute('data-jabatan');
                    const row = e.target.closest('.pegawai-row');
                    const jabatanSelect = row.querySelector('.jabatan-select');

                    if (defaultJabatan && jabatanSelect) {
                        for (let i = 0; i < jabatanSelect.options.length; i++) {
                            if (jabatanSelect.options[i].value.toLowerCase() === defaultJabatan.toLowerCase()) {
                                jabatanSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }
            });
        }

        // --- SCRIPT SURAT AKTIF BELAJAR ---
        const selectSiswa = document.getElementById('select-siswa-edit');
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
                }
            }
            selectSiswa.addEventListener('change', updateSiswaHiddenFields);
            if (selectSiswa.value) updateSiswaHiddenFields();
        }

        // --- SCRIPT SURAT AKTIF MENGAJAR ---
        const selectGuru = document.getElementById('select-guru-edit');
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
                }
            }
            selectGuru.addEventListener('change', updateGuruHiddenFields);
            if (selectGuru.value) updateGuruHiddenFields();
        }
    });
</script>
@endpush