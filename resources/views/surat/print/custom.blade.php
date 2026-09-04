<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Custom - {{ $surat->nomor_surat }}</title>

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
            line-height: 1.35;
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
        .kop-meta td {
            border: none !important;
            padding: 0;
        }
        .line-single {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }
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
            margin-bottom: 18px;
        }

        /* Meta Surat */
        .meta-surat {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse !important;
            border: none !important;
        }
        .meta-surat td {
            border: none !important;
            vertical-align: top;
            padding: 2px 0;
            font-size: 11pt;
        }

        /* Content Body */
        .content {
            margin-bottom: 20px;
            text-align: justify;
            font-size: 11pt;
            line-height: 1.4;
        }

        .table-detail {
            width: 100%;
            margin: 12px 0 12px 20px;
            border-collapse: collapse !important;
            border: none !important;
        }
        .table-detail td {
            border: none !important;
            padding: 3px 6px;
            vertical-align: top;
            font-size: 11pt;
        }

        /* Box TTD */
        .ttd-container {
            width: 100%;
            margin-top: 30px;
            font-family: 'Times New Roman', Times, serif;
        }
        .ttd-box {
            float: right;
            width: 42%;
            text-align: center;
            font-size: 11pt;
            line-height: 1.3;
        }
        .clear {
            clear: both;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #e9ecef; padding: 10px; text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">Cetak Surat / Save PDF</button>
    </div>

    <div id="document-render">
        <!-- KOP SURAT FRANKLIN GOTHIC MEDIUM -->
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

        @php $p = $surat->payload_detail ?? []; @endphp

        <!-- META SURAT (Nomor, Lampiran, Hal & Tanggal) -->
        <table class="meta-surat">
            <tr>
                <td style="width: 12%;">Nomor</td>
                <td style="width: 3%;">:</td>
                <td style="width: 45%;">{{ $surat->nomor_surat }}</td>
                <td style="width: 40%; text-align: right;">{{ $pengaturan?->kota ?? 'Tonjong' }}, {{ \Carbon\Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>{{ $surat->lampiran ?? '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $surat->perihal }}</strong></td>
                <td></td>
            </tr>
        </table>

        <!-- TUJUAN PENERIMA -->
        <div style="margin-bottom: 20px;">
            Kepada Yth.<br>
            <strong>{{ $surat->tujuan_penerima ?? 'Bapak/Ibu Orang Tua/Wali Murid' }}</strong><br>
            di Tempat
        </div>

        <!-- ISI SURAT CUSTOM -->
        <div class="content">
            <p>{!! nl2br(e($p['isi_surat'] ?? 'Dengan hormat, bersama surat ini kami sampaikan hal-hal sebagai berikut.')) !!}</p>
            
            @if(!empty($p['waktu_acara']) || !empty($p['tempat_acara']))
            <table class="table-detail">
                @if(!empty($p['waktu_acara']))
                <tr>
                    <td style="width: 25%;"><strong>Waktu / Tanggal</strong></td>
                    <td style="width: 5%;">:</td>
                    <td>{{ $p['waktu_acara'] }}</td>
                </tr>
                @endif
                @if(!empty($p['tempat_acara']))
                <tr>
                    <td><strong>Tempat / Lokasi</strong></td>
                    <td>:</td>
                    <td>{{ $p['tempat_acara'] }}</td>
                </tr>
                @endif
            </table>
            @endif

            <p style="margin-top: 12px;">Demikian surat ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
        </div>

        <!-- TTD PENANDATANGAN -->
        <div class="ttd-container">
            <div class="ttd-box">
                <p>{{ $surat->penandatangan_jabatan ?? 'Kepala Sekolah' }},</p>
                <br><br><br><br>
                <p><strong><u>{{ $surat->penandatangan_nama ?? $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.' }}</u></strong></p>
                <span>NIP. {{ $pengaturan?->nip_kepala_sekolah ?? '-' }}</span>
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
                    filename:     'Surat_Custom_{{ str_replace(['/', '\\'], '-', $surat->nomor_surat) }}.pdf',
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