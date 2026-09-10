@extends('layouts.app')

@push('styles')
<style>
    .white-space-pre-line {
        white-space: pre-line;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow-sm rounded-3">
                
                <!-- HEADER CARD -->
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text text-primary"></i>
                        <span>Detail Surat: {{ strtoupper(str_replace('_', ' ', $surat->jenis_surat)) }}</span>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('surat.print', $surat->id) }}" target="_blank" class="btn btn-sm btn-success fw-semibold d-inline-flex align-items-center gap-1">
                            <i class="bi bi-printer-fill"></i>
                            <span>Cetak Surat / PDF</span>
                        </a>
                        <a href="{{ route('surat.edit', $surat->id) }}" class="btn btn-sm btn-warning text-white fw-semibold d-inline-flex align-items-center gap-1">
                            <i class="bi bi-pencil-square"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('surat.index', ['jenis' => $surat->jenis_surat]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @php 
                        $p = $surat->payload_detail ?? [];
                        if (is_string($p)) {
                            $p = json_decode($p, true) ?? [];
                        }
                    @endphp

                    <!-- INFORMASI UTAMA SURAT -->
                    <div class="bg-light p-3 rounded-3 border mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Nomor Surat</span>
                                <strong class="fs-6 text-dark">{{ $surat->nomor_surat }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Tanggal Surat</span>
                                <strong class="fs-6 text-dark">
                                    {{ \Carbon\Carbon::parse($surat->tgl_surat ?? $surat->tanggal_surat)->translatedFormat('d F Y') }}
                                </strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Lampiran</span>
                                <strong class="fs-6 text-dark">{{ $surat->lampiran ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Perihal</span>
                                <strong class="fs-6 text-dark">{{ $surat->perihal }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- RINGKASAN KHUSUS SURAT AKTIF BELAJAR -->
                    @if($surat->jenis_surat === 'aktif_belajar')
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span>Detail Data Siswa / Peserta Didik</span>
                        </h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td style="width: 200px;" class="text-muted">Nama Siswa</td>
                                <td style="width: 15px;">:</td>
                                <td class="fw-bold text-dark">{{ $p['nama_siswa'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">NIS / NISN</td>
                                <td>:</td>
                                <td>{{ $p['nis'] ?? '-' }} / {{ $p['nisn'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">NIK</td>
                                <td>:</td>
                                <td>{{ $p['nik'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tempat, Tanggal Lahir</td>
                                <td>:</td>
                                <td>{{ $p['ttl'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Kelamin</td>
                                <td>:</td>
                                <td>{{ $p['jenis_kelamin'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kelas</td>
                                <td>:</td>
                                <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">{{ $p['kelas'] ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Orang Tua / Wali</td>
                                <td>:</td>
                                <td>{{ $p['nama_orang_tua'] ?? $p['nama_ayah'] ?? $p['nama_ibu'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat Siswa</td>
                                <td>:</td>
                                <td>{{ $p['alamat'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Keperluan Surat</td>
                                <td>:</td>
                                <td class="fw-semibold text-primary">{{ $p['keperluan'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    @endif

                    <!-- RINGKASAN KHUSUS SURAT AKTIF MENGAJAR -->
                    @if($surat->jenis_surat === 'aktif_mengajar')
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-person-workspace"></i>
                            <span>Detail Data Guru / Pendidik</span>
                        </h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td style="width: 200px;" class="text-muted">Nama Guru / Pegawai</td>
                                <td style="width: 15px;">:</td>
                                <td class="fw-bold text-dark">{{ $p['nama_guru'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tempat, Tanggal Lahir</td>
                                <td>:</td>
                                <td>{{ $p['ttl'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">NUPTK / NIP</td>
                                <td>:</td>
                                <td>{{ $p['nuptk'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">NRG / NBM</td>
                                <td>:</td>
                                <td>{{ $p['nrg'] ?? '-' }} / {{ $p['nbm'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Pendidikan Terakhir</td>
                                <td>:</td>
                                <td>{{ $p['pendidikan_terakhir'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Satminkal / Induk</td>
                                <td>:</td>
                                <td>{{ $p['satminkal'] ?? 'SMP Muhammadiyah Tonjong (Induk)' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat Guru</td>
                                <td>:</td>
                                <td>{{ $p['alamat'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    @endif

                    <!-- RINGKASAN KHUSUS SPPD -->
                    @if($surat->jenis_surat === 'sppd')
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>Detail Perjalanan Dinas (SPPD)</span>
                        </h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Tempat Tujuan</span>
                                <strong>{{ $p['tempat_tujuan'] ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Maksud Perjalanan Dinas</span>
                                <strong>{{ $p['maksud_dinas'] ?? '-' }}</strong>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted small d-block">Tanggal Berangkat</span>
                                <strong>{{ !empty($p['tgl_berangkat']) ? \Carbon\Carbon::parse($p['tgl_berangkat'])->translatedFormat('d F Y') : '-' }}</strong>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted small d-block">Tanggal Kembali</span>
                                <strong>{{ !empty($p['tgl_kembali']) ? \Carbon\Carbon::parse($p['tgl_kembali'])->translatedFormat('d F Y') : '-' }}</strong>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted small d-block">Transportasi</span>
                                <strong>{{ $p['transportasi'] ?? 'Pribadi' }}</strong>
                            </div>
                        </div>

                        <h6 class="fw-semibold text-dark mt-4 mb-2">Pegawai yang Ditugaskan:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Jabatan Tugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($p['pegawai_list'] ?? [] as $index => $peg)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $peg['nama'] ?? '-' }}</td>
                                            <td>{{ $peg['jabatan'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data pegawai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- RINGKASAN KHUSUS SURAT UNDANGAN, PEMBERITAHUAN, & PERMOHONAN -->
                    @if(in_array($surat->jenis_surat, ['und', 'pbh', 'pmh']))
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-envelope-open-fill"></i>
                            <span>Detail Surat {{ strtoupper($surat->jenis_surat) }}</span>
                        </h6>
                        <table class="table table-sm table-borderless mb-3">
                            <tr>
                                <td style="width: 200px;" class="text-muted">Kepada Yth. (Penerima)</td>
                                <td style="width: 15px;">:</td>
                                <td class="fw-bold text-dark">{{ $p['penerima'] ?? $surat->tujuan_penerima ?? $surat->tujuan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tempat Penerima</td>
                                <td>:</td>
                                <td>{{ $p['tempat_penerima'] ?? 'di - Tempat' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Salam Pembuka</td>
                                <td>:</td>
                                <td>{{ $p['salam_pembuka'] ?? "Assalamu'alaikum Wr. Wb." }}</td>
                            </tr>
                        </table>

                        @if(!empty($p['paragraf_pembuka']))
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Paragraf Pembuka:</span>
                            <div class="p-3 bg-light rounded border text-dark white-space-pre-line">{{ $p['paragraf_pembuka'] }}</div>
                        </div>
                        @endif

                        <!-- Agenda Acara / Waktu Kegiatan -->
                        @if(!empty($p['hari_tanggal']) || !empty($p['waktu_acara']) || !empty($p['tempat_acara']) || !empty($p['agenda_acara']))
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="fw-bold text-secondary mb-2">Informasi Pelaksanaan Kegiatan / Acara:</div>
                            <table class="table table-sm table-borderless mb-0">
                                @if(!empty($p['hari_tanggal']))
                                <tr>
                                    <td style="width: 180px;" class="text-muted">Hari, Tanggal</td>
                                    <td style="width: 15px;">:</td>
                                    <td class="fw-semibold">{{ $p['hari_tanggal'] }}</td>
                                </tr>
                                @endif
                                @if(!empty($p['waktu_acara']))
                                <tr>
                                    <td class="text-muted">Waktu / Jam</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ $p['waktu_acara'] }}</td>
                                </tr>
                                @endif
                                @if(!empty($p['tempat_acara']))
                                <tr>
                                    <td class="text-muted">Tempat Acara</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ $p['tempat_acara'] }}</td>
                                </tr>
                                @endif
                                @if(!empty($p['agenda_acara']))
                                <tr>
                                    <td class="text-muted">Agenda / Acara</td>
                                    <td>:</td>
                                    <td class="fw-semibold text-primary">{{ $p['agenda_acara'] }}</td>
                                </tr>
                                @endif
                                @if(!empty($p['keterangan_acara']))
                                <tr>
                                    <td class="text-muted">Keterangan Tambahan</td>
                                    <td>:</td>
                                    <td>{{ $p['keterangan_acara'] }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        @endif

                        @if(!empty($p['paragraf_penutup']))
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Paragraf Penutup:</span>
                            <div class="p-3 bg-light rounded border text-dark white-space-pre-line">{{ $p['paragraf_penutup'] }}</div>
                        </div>
                        @endif

                        <!-- Tembusan Surat Dinamis & Otomatis Pertinggal -->
                        @php
                            $tembusanRaw = $p['tembusan_list'] ?? $p['tembusan'] ?? [];
                            if (is_string($tembusanRaw)) {
                                $tembusanRaw = explode(',', $tembusanRaw);
                            }

                            // Clean nomor awal dan filter string Pertinggal bawaan
                            $cleanTembusan = array_values(array_filter(array_map(function($item) {
                                return preg_replace('/^\d+\.\s*/', '', trim($item));
                            }, (array)$tembusanRaw), function($item) {
                                $lower = strtolower($item);
                                return !empty($item) && $lower !== 'pertinggal (arsip)' && $lower !== 'pertinggal';
                            }));

                            // Selalu masukkan Pertinggal (Arsip) di akhir
                            $cleanTembusan[] = "Pertinggal (Arsip)";
                        @endphp

                        @if(count($cleanTembusan) > 0)
                        <div class="mt-3">
                            <span class="text-muted small d-block mb-1">Tembusan Surat:</span>
                            <ol class="mb-0 ps-3 text-dark">
                                @foreach($cleanTembusan as $tembusan)
                                    <li>{{ $tembusan }}</li>
                                @endforeach
                            </ol>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- RINGKASAN KHUSUS SURAT CUSTOM -->
                    @if($surat->jenis_surat === 'custom')
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Detail Surat Custom</span>
                        </h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Tujuan / Penerima</span>
                                <strong>{{ $surat->tujuan_penerima ?? $surat->tujuan ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Waktu / Tempat Acara</span>
                                <strong>{{ $p['waktu_acara'] ?? '-' }}</strong>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block mb-1">Isi Surat</span>
                                <div class="p-3 bg-light rounded border text-dark white-space-pre-line">{{ $p['isi_surat'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- PENANDATANGAN -->
                    <div class="border-top pt-3 text-end">
                        <span class="text-muted small d-block">Penandatangan Surat:</span>
                        <strong class="text-dark">{{ $surat->penandatangan_nama ?? $p['penandatangan_nama'] ?? 'IRFAN TUNZILA, S. Ag.' }}</strong>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection