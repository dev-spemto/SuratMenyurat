<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPPD & Surat Tugas - {{ $surat->nomor_surat }}</title>
    
    <!-- Library HTML2PDF untuk Otomatis Download PDF Client-Side -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box !important;
        }

        @page {
            size: A4;
            margin: 1.2cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        #document-render {
            width: 100%;
            max-width: 100%;
            background: #fff;
            margin: 0 auto;
            padding: 2px;
        }

        /* Kop Surat Franklin Gothic Medium Presisi */
        .kop-container {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important;
            color: #000;
            width: 100%;
            margin-bottom: 12px;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse !important;
            border: none !important;
        }
        .kop-table td {
            vertical-align: middle !important;
            border: none !important;
            padding: 0;
        }
        .kop-text {
            text-align: center;
            padding-top: 12px;
            padding-bottom: 2px;
        }
        .kop-text .h4-header, .kop-text .h3-header {
            margin: 0;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            letter-spacing: 0.1px;
        }
        .kop-text .h2-header {
            margin: 1px 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            letter-spacing: 0.2px;
        }
        .kop-text .akreditasi {
            font-size: 9.5pt;
            font-weight: bold;
            margin-top: 1px;
        }
        .kop-meta {
            width: 100%;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 2px;
            border-collapse: collapse !important;
            border: none !important;
        }
        .kop-meta td { border: none !important; padding: 0; }
        .line-single { border-top: 1px solid #000; margin-bottom: 2px; }
        .kop-alamat {
            font-size: 10pt;
            font-weight: normal;
            text-align: center;
            line-height: 1.35;
        }
        .kop-alamat a, .kop-alamat .link-blue {
            color: #0000ff !important;
            text-decoration: underline !important;
        }
        .line-double {
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 1px;
            margin-top: 2px;
            margin-bottom: 12px;
        }

        /* Judul Surat & Garis Underline Berjarak Rapi */
        .judul-surat { text-align: center; margin-bottom: 14px; }
        .judul-surat h3 {
            margin: 0;
            display: inline-block;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: none !important;
            border-bottom: 1.5px solid #000;
            padding-bottom: 3px;
            letter-spacing: 0.5px;
        }
        .judul-surat p {
            margin: 4px 0 0 0;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            font-size: 12pt;
        }

        /* Table Utama SPPD */
        table.table-sppd {
            width: 100%;
            border-collapse: collapse !important;
            margin-bottom: 15px;
            border: 1px solid #000 !important;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            table-layout: fixed;
        }
        table.table-sppd th, table.table-sppd td {
            border: 1px solid #000 !important;
            padding: 4px 6px;
            vertical-align: top;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            font-size: 11pt;
            line-height: 1.25;
            color: #000;
            word-wrap: break-word;
        }
        table.table-sppd td.num { width: 4%; text-align: left; padding-right: 2px; }
        table.table-sppd td.label { width: 42%; }

        /* Sub Table Multi Pegawai */
        table.sub-table-detail {
            width: 100%;
            border-collapse: collapse !important;
            border: none !important;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
        }
        table.sub-table-detail td {
            border: none !important;
            padding: 0 !important;
            vertical-align: top;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            font-size: 11pt;
            line-height: 1.25;
        }

        /* Table Kunjungan Pejabat / Lembaga */
        table.table-kunjungan {
            width: 100%;
            border-collapse: collapse !important;
            margin-top: 4px;
            border: 1px solid #000 !important;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            table-layout: fixed;
        }
        table.table-kunjungan th, table.table-kunjungan td {
            border: 1px solid #000 !important;
            padding: 4px 5px;
            text-align: center;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
            font-size: 10.5pt;
            font-weight: normal !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Tabel Personil Surat Tugas (Halaman 2) */
        table.table-personil-tugas {
            width: 100%;
            margin: 12px 0;
            border-collapse: collapse !important;
            font-family: 'Times New Roman', Times, serif;
            table-layout: fixed;
        }
        table.table-personil-tugas th, table.table-personil-tugas td {
            border: 1px solid #000 !important;
            padding: 5px 6px;
            font-size: 11pt;
            vertical-align: middle;
            word-wrap: break-word;
        }
        table.table-personil-tugas th {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Detail Waktu & Tempat Surat Tugas */
        table.table-kegiatan-tugas {
            width: 100%;
            margin: 10px 0 15px 10px;
            border-collapse: collapse !important;
            border: none !important;
            font-family: 'Times New Roman', Times, serif;
        }
        table.table-kegiatan-tugas td {
            border: none !important;
            padding: 3px 4px;
            vertical-align: top;
            font-size: 11pt;
        }

        /* Box TTD */
        .ttd-container {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
        }
        .ttd-box {
            float: right;
            width: 45%;
            text-align: center;
            font-size: 11pt;
            font-family: 'Bookman Old Style', 'Bookman', 'URW Bookman L', serif !important;
        }

        .clear { clear: both; }

        .page-break {
            page-break-before: always;
            break-before: page;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #e9ecef; padding: 10px; text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">Cetak Surat (SPPD & Surat Tugas)</button>
    </div>

    <div id="document-render">
        @php
            $cleanGelar = function($nama) {
                if (empty($nama)) return '-';
                if (class_exists('App\Models\Pegawai')) {
                    $nama = \App\Models\Pegawai::formatGelar($nama);
                }
                return preg_replace('/\.{2,}/', '.', trim($nama));
            };

            $p = $surat->payload_detail ?? [];
            $pegawais = $p['pegawai_list'] ?? [];
            $tglBerangkat = isset($p['tgl_berangkat']) ? \Carbon\Carbon::parse($p['tgl_berangkat']) : \Carbon\Carbon::parse($surat->tgl_surat ?? $surat->tanggal_surat);
            $tglKembali   = isset($p['tgl_kembali']) ? \Carbon\Carbon::parse($p['tgl_kembali']) : null;
        @endphp

        <!-- HALAMAN 1: SPPD -->
        <div class="kop-container">
            <table class="kop-table">
                <tr>
                    <td style="width: 2.2cm; text-align: left; vertical-align: middle;">
                        <img src="{{ asset('images/logo-muhammadiyah.png') }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Muhammadiyah">
                    </td>
                    <td class="kop-text" style="vertical-align: middle;">
                        <div class="h4-header">{{ $pengaturan?->nama_majelis ?? 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL' }}</div>
                        <div class="h3-header">{{ $pengaturan?->pimpinan ?? 'PIMPINAN DAERAH MUHAMMADIYAH BREBES' }}</div>
                        <div class="h2-header">{{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</div>
                        <div class="akreditasi">" TERAKREDITASI A "</div>
                    </td>
                    <td style="width: 2.2cm; text-align: right; vertical-align: middle;">
                        @if ($pengaturan?->logo)
                            <img src="{{ asset('storage/' . $pengaturan->logo) }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Sekolah">
                        @else
                            <img src="{{ asset('images/logo-sekolah.png') }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Sekolah">
                        @endif
                    </td>
                </tr>
            </table>

            <table class="kop-meta">
                <tr>
                    <td style="width: 50%; text-align: left;">NPSN: {{ $pengaturan?->npsn ?? '20326564' }}</td>
                    <td style="width: 50%; text-align: right;">NSS: {{ $pengaturan?->nss ?? '202032906045' }}</td>
                </tr>
            </table>

            <div class="line-single"></div>

            <div class="kop-alamat">
                Alamat: {{ $pengaturan?->alamat ?? 'Jl. Raya Linggapura No. 46 – Tonjong – Brebes' }} &#128231; 52271 &#9742; {{ $pengaturan?->nomor_hp ?? '(+62) 851-850-333-77' }}<br>
                <strong>E-mail:</strong> <a href="mailto:{{ $pengaturan?->email ?? 'smpmuhitonjong@gmail.com' }}" class="link-blue">{{ $pengaturan?->email ?? 'smpmuhitonjong@gmail.com' }}</a> &nbsp;&nbsp; <strong>Website:</strong> <a href="{{ $pengaturan?->website ?? 'https://smpmuhtonjong.sch.id' }}" class="link-blue">{{ $pengaturan?->website ?? 'https://smpmuhtonjong.sch.id' }}</a>
            </div>

            <div class="line-double"></div>
        </div>

        <div class="judul-surat">
            <h3>SURAT PERINTAH PERJALANAN DINAS ( S P P D )</h3>
            <p>Nomor: {{ $surat->nomor_surat }}</p>
        </div>

        <!-- TABEL UTAMA SPPD -->
        <table class="table-sppd">
            <tr>
                <td class="num">1.</td>
                <td class="label">Pejabat yang memberi perintah perjalanan dinas</td>
                <td>Kepala SMP Muhammadiyah Tonjong<br>Kabupaten Brebes</td>
            </tr>
            <tr>
                <td class="num">2.</td>
                <td class="label">Nama Pegawai yang diperintah mengadakan perjalanan dinas</td>
                <td>
                    @if(count($pegawais) > 0)
                        <table class="sub-table-detail">
                            @foreach($pegawais as $index => $peg)
                                <tr>
                                    <td style="width: 20px;"><strong>{{ $index + 1 }}.</strong></td>
                                    <td><strong>{{ $cleanGelar($peg['nama']) }}</strong></td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <strong>1. {{ $cleanGelar($surat->tujuan_penerima ?? '-') }}</strong>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num">3.</td>
                <td class="label">Jabatan / NUPTK pegawai yang diperintahkan</td>
                <td>
                    @if(count($pegawais) > 0)
                        <table class="sub-table-detail">
                            @foreach($pegawais as $index => $peg)
                                <tr>
                                    <td style="width: 20px;">{{ $index + 1 }}.</td>
                                    <td>{{ $peg['jabatan'] ?? 'Guru' }}</td>
                                    <td style="text-align: right;">/ {{ $peg['nip'] ?? $peg['nuptk_nip'] ?? $peg['nuptk'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <table class="sub-table-detail">
                            <tr>
                                <td style="width: 20px;">1.</td>
                                <td>{{ $p['jabatan'] ?? 'Guru' }}</td>
                                <td style="text-align: right;">/ {{ $p['nuptk'] ?? '-' }}</td>
                            </tr>
                        </table>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="num">4.</td>
                <td class="label">Perjalanan dinas Yang Diperintahkan</td>
                <td>
                    <table class="sub-table-detail">
                        <tr>
                            <td style="width: 60px;">Dari</td>
                            <td style="width: 15px;">:</td>
                            <td>SMP Muhammadiyah Tonjong</td>
                        </tr>
                        <tr>
                            <td>Ke</td>
                            <td>:</td>
                            <td>{{ $p['tempat_tujuan'] ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="num">5.</td>
                <td class="label">Perjalanan yang direncanakan</td>
                <td>
                    @php
                        if ($tglBerangkat && $tglKembali) {
                            $jumlahHari = $tglBerangkat->diffInDays($tglKembali) + 1;
                        } else {
                            $jumlahHari = 1;
                        }

                        if (!function_exists('terbilangHari')) {
                            function terbilangHari($angka) {
                                $baca = [0 => 'nol', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima', 6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan', 10 => 'sepuluh', 11 => 'sebelas'];
                                if ($angka < 12) return $baca[$angka] ?? $angka;
                                elseif ($angka < 20) return $baca[$angka - 10] . ' belas';
                                elseif ($angka < 100) {
                                    $puluh = (int) floor($angka / 10);
                                    $sisa = $angka % 10;
                                    if ($sisa === 0) return $baca[$puluh] . ' puluh';
                                    return $baca[$puluh] . ' ' . $baca[$sisa];
                                } elseif ($angka == 100) return 'seratus';
                                return (string) $angka;
                            }
                        }

                        $terbilangText = terbilangHari($jumlahHari);
                    @endphp

                    <table class="sub-table-detail">
                        <tr>
                            <td style="width: 120px;">Selama</td>
                            <td style="width: 15px;">:</td>
                            <td>{{ $jumlahHari }} ({{ $terbilangText }}) hari</td>
                        </tr>
                        <tr>
                            <td>Dari tanggal</td>
                            <td>:</td>
                            <td>{{ $tglBerangkat ? $tglBerangkat->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Sampai dengan</td>
                            <td>:</td>
                            <td>{{ $tglKembali ? $tglKembali->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="num">6.</td>
                <td class="label">Maksud mengadakan perjalanan</td>
                <td><strong>{{ $p['maksud_dinas'] ?? $surat->perihal }}</strong></td>
            </tr>
            <tr>
                <td class="num">7.</td>
                <td class="label">Perhitungan biaya perjalanan</td>
                <td>Perjalanan Dinas</td>
            </tr>
            <tr>
                <td class="num">8.</td>
                <td class="label">Keterangan</td>
                <td>{!! nl2br(e($p['keterangan'] ?? '')) !!}</td>
            </tr>
        </table>

        <!-- TTD SPPD -->
        <div class="ttd-container">
            <div class="ttd-box">
                {{ $pengaturan?->kota ?? 'Tonjong' }}, {{ \Carbon\Carbon::parse($surat->tgl_surat ?? $surat->tanggal_surat)->translatedFormat('d F Y') }}<br>
                Kepala Sekolah,<br><br><br><br>
                <strong><u>{{ $cleanGelar($surat->penandatangan_nama ?? $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.') }}</u></strong><br>
                <span>NIP. {{ $surat->penandatangan_nip ?? $pengaturan?->nip_kepala_sekolah ?? '-' }}</span>
            </div>
            <div class="clear"></div>
        </div>

        <!-- TABEL KUNJUNGAN PEJABAT / LEMBAGA -->
        <div style="font-size: 10pt; font-weight: normal; margin-bottom: 4px;">
            DARI PEJABAT / LEMBAGA YANG DIKUNJUNGI
        </div>
        <table class="table-kunjungan">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 28%; vertical-align: middle; font-weight: normal; white-space: nowrap;">Kantor yang berwenang</th>
                    <th colspan="2" style="width: 30%; font-weight: normal;">Datang</th>
                    <th colspan="3" style="width: 42%; font-weight: normal;">Kembali</th>
                </tr>
                <tr>
                    <th style="width: 15%; font-weight: normal;">Tanggal</th>
                    <th style="width: 15%; font-weight: normal;">Tanda Tangan</th>
                    <th style="width: 15%; font-weight: normal;">Tanggal</th>
                    <th style="width: 13%; font-weight: normal;">Dengan Kendaraan</th>
                    <th style="width: 14%; font-weight: normal;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left; height: 45px; vertical-align: top; word-wrap: break-word; overflow-wrap: break-word;">
                        {{ $p['tempat_tujuan'] ?? '-' }}
                    </td>
                    <td style="vertical-align: top; white-space: nowrap;">{{ isset($p['tgl_berangkat']) ? \Carbon\Carbon::parse($p['tgl_berangkat'])->format('d/m/Y') : '' }}</td>
                    <td></td>
                    <td style="vertical-align: top; white-space: nowrap;">{{ isset($p['tgl_kembali']) ? \Carbon\Carbon::parse($p['tgl_kembali'])->format('d/m/Y') : '' }}</td>
                    <td style="vertical-align: top;">{{ $p['transportasi'] ?? 'Pribadi' }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>


        <!-- HALAMAN 2: SURAT TUGAS -->
        <div class="page-break"></div>

        <div class="kop-container">
            <table class="kop-table">
                <tr>
                    <td style="width: 2.2cm; text-align: left; vertical-align: middle;">
                        <img src="{{ asset('images/logo-muhammadiyah.png') }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Muhammadiyah">
                    </td>
                    <td class="kop-text" style="vertical-align: middle;">
                        <div class="h4-header">{{ $pengaturan?->nama_majelis ?? 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL' }}</div>
                        <div class="h3-header">{{ $pengaturan?->pimpinan ?? 'PIMPINAN DAERAH MUHAMMADIYAH BREBES' }}</div>
                        <div class="h2-header">{{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</div>
                        <div class="akreditasi">" TERAKREDITASI A "</div>
                    </td>
                    <td style="width: 2.2cm; text-align: right; vertical-align: middle;">
                        @if ($pengaturan?->logo)
                            <img src="{{ asset('storage/' . $pengaturan->logo) }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Sekolah">
                        @else
                            <img src="{{ asset('images/logo-sekolah.png') }}" style="width: 2.00cm; height: 2.00cm; object-fit: contain;" alt="Logo Sekolah">
                        @endif
                    </td>
                </tr>
            </table>
            <table class="kop-meta">
                <tr>
                    <td style="width: 50%; text-align: left;">NPSN: {{ $pengaturan?->npsn ?? '20326564' }}</td>
                    <td style="width: 50%; text-align: right;">NSS: {{ $pengaturan?->nss ?? '202032906045' }}</td>
                </tr>
            </table>
            <div class="line-single"></div>
            <div class="kop-alamat">
                Alamat: {{ $pengaturan?->alamat ?? 'Jl. Raya Linggapura No. 46 – Tonjong – Brebes' }} &#128231; 52271 &#9742; {{ $pengaturan?->nomor_hp ?? '(+62) 851-850-333-77' }}<br>
                <strong>E-mail:</strong> <a href="mailto:{{ $pengaturan?->email ?? 'smpmuhitonjong@gmail.com' }}" class="link-blue">{{ $pengaturan?->email ?? 'smpmuhitonjong@gmail.com' }}</a> &nbsp;&nbsp; <strong>Website:</strong> <a href="{{ $pengaturan?->website ?? 'https://smpmuhtonjong.sch.id' }}" class="link-blue">{{ $pengaturan?->website ?? 'https://smpmuhtonjong.sch.id' }}</a>
            </div>
            <div class="line-double"></div>
        </div>

        <div class="judul-surat" style="margin-bottom: 20px;">
            <h3 style="font-family: 'Times New Roman', Times, serif !important; text-transform: uppercase;">SURAT PERINTAH TUGAS</h3>
            <p style="font-family: 'Times New Roman', Times, serif !important;">Nomor : {{ $surat->nomor_surat }}</p>
        </div>

        <div style="font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.4; text-align: justify;">
            <p style="margin-bottom: 10px;">
                Yang bertanda tangan di bawah ini, Kepala Sekolah SMP Muhammadiyah Tonjong, dengan ini
            </p>
            <p style="text-align: center; font-weight: bold; font-size: 12pt; margin: 8px 0 14px 0; letter-spacing: 1px;">
                MENUGASKAN
            </p>
            <p style="margin-bottom: 8px;">
                <u>Kepada :</u>
            </p>

            <table class="table-personil-tugas">
                <thead>
                    <tr>
                        <th style="width: 6%; white-space: nowrap;">NO</th>
                        <th style="width: 29%;">NAMA</th>
                        <th style="width: 18%;">JABATAN</th>
                        <th style="width: 25%;">NUPTK</th>
                        <th style="width: 22%;">UNIT KERJA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $index => $peg)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $cleanGelar($peg['nama']) }}</strong></td>
                            <td style="text-align: center;">{{ $peg['jabatan'] ?? '-' }}</td>
                            <td style="text-align: center; white-space: nowrap;">{{ $peg['nip'] ?? $peg['nuptk_nip'] ?? $peg['nuptk'] ?? '-' }}</td>
                            <td style="text-align: center; line-height: 1.25;">
                                SMP<br>MUHAMMADIYAH<br>TONJONG
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td><strong>{{ $cleanGelar($surat->tujuan_penerima ?? '-') }}</strong></td>
                            <td style="text-align: center;">Guru</td>
                            <td style="text-align: center; white-space: nowrap;">-</td>
                            <td style="text-align: center; line-height: 1.25;">
                                SMP<br>MUHAMMADIYAH<br>TONJONG
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p style="margin-top: 16px; margin-bottom: 8px;">
                Untuk melaksanakan kegiatan <strong>{{ $p['maksud_dinas'] ?? $surat->perihal }}</strong> yang akan dilaksanakan pada:
            </p>

            <table class="table-kegiatan-tugas">
                <tr>
                    <td style="width: 140px;">Hari, Tanggal</td>
                    <td style="width: 15px;">:</td>
                    <td>{{ $tglBerangkat ? $tglBerangkat->translatedFormat('l, d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 140px;">Waktu</td>
                    <td style="width: 15px;">:</td>
                    <td>07:30 WIB s.d. Selesai</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td>{{ $p['tempat_tujuan'] ?? '-' }}</td>
                </tr>
            </table>

            <p style="margin-top: 18px;">
                Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan sebaik-baiknya.
            </p>
        </div>

        <div class="ttd-container" style="margin-top: 35px;">
            <div class="ttd-box" style="font-family: 'Times New Roman', Times, serif !important;">
                {{ $pengaturan?->kota ?? 'Tonjong' }}, {{ \Carbon\Carbon::parse($surat->tgl_surat ?? $surat->tanggal_surat)->translatedFormat('d F Y') }}<br>
                Kepala Sekolah,<br><br><br><br>
                <strong><u>{{ $cleanGelar($surat->penandatangan_nama ?? $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.') }}</u></strong><br>
                <span>NIP. {{ $surat->penandatangan_nip ?? $pengaturan?->nip_kepala_sekolah ?? '-' }}</span>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!-- SKRIP OTOMATIS DOWNLOAD PDF -->
    @if(request('download') === 'pdf')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const element = document.getElementById('document-render');
                const opt = {
                    margin:       [0.4, 0.4, 0.4, 0.4],
                    filename:     'SPPD_{{ str_replace(['/', '\\'], '-', $surat->nomor_surat) }}.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { 
                        scale: 2, 
                        useCORS: true, 
                        scrollX: 0, 
                        scrollY: 0,
                        windowWidth: document.documentElement.offsetWidth
                    },
                    jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(element).save().then(() => {
                    setTimeout(() => { window.close(); }, 1500);
                });
            });
        </script>
    @endif

</body>
</html>