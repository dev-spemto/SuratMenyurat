<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Ket. Aktif Mengajar - {{ $surat->nomor_surat }}</title>
    
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

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin-bottom: 18px;
        }
        .judul-surat h3 {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .judul-surat p {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }

        /* Isi Surat */
        .content-body {
            font-size: 11pt;
            text-align: justify;
            line-height: 1.35;
        }

        /* Tabel Detail Data Guru Presisi */
        table.table-detail {
            width: 100%;
            margin: 10px 0 12px 20px;
            border-collapse: collapse !important;
            border: none !important;
        }
        table.table-detail td {
            border: none !important;
            padding: 2px 4px;
            vertical-align: top;
            font-size: 11pt;
            line-height: 1.3;
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

        <!-- JUDUL SURAT -->
        <div class="judul-surat">
            <h3>SURAT KETERANGAN AKTIF MENGAJAR</h3>
            <p>Nomor : {{ $surat->nomor_surat }}</p>
        </div>

        @php
            $p = $surat->payload_detail ?? [];

            // Ambil TMT dari berbagai key payload yang mungkin digunakan
            $rawTmt = $p['tmt'] ?? $p['tgl_mulai_tugas'] ?? null;

            // Format TMT ke Bahasa Indonesia
            if (!empty($rawTmt) && $rawTmt !== '-') {
                try {
                    $tmtFormatted = \Carbon\Carbon::parse($rawTmt)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    $tmtFormatted = $rawTmt;
                }
            } else {
                $tmtFormatted = '-';
            }
        @endphp

        <!-- ISI SURAT -->
        <div class="content-body">
            <p style="margin-bottom: 8px;">
                Kepala SMP Muhammadiyah Tonjong Kabupaten Brebes, menerangkan bahwa:
            </p>

            <table class="table-detail">
                <tr>
                    <td style="width: 180px;">N a m a</td>
                    <td style="width: 15px;">:</td>
                    <td><strong>{{ $p['nama_guru'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Tempat Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $p['ttl'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NUPTK</td>
                    <td>:</td>
                    <td>{{ $p['nuptk'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NRG</td>
                    <td>:</td>
                    <td>{{ $p['nrg'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NBM</td>
                    <td>:</td>
                    <td>{{ $p['nbm'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $p['nik'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $p['jenis_kelamin'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pendidikan Terakhir/Jur.</td>
                    <td>:</td>
                    <td>{{ $p['pendidikan_terakhir'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Mulai Tugas</td>
                    <td>:</td>
                    <td>{{ $tmtFormatted }}</td>
                </tr>
                <tr>
                    <td>A l a m a t</td>
                    <td>:</td>
                    <td>{{ $p['alamat'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Satminkal</td>
                    <td>:</td>
                    <td>{{ $p['satminkal'] ?? 'SMP Muhammadiyah Tonjong (Induk)' }}</td>
                </tr>
                <tr>
                    <td>Jabatan/Status</td>
                    <td>:</td>
                    <td>{{ $p['jabatan_status'] ?? 'Guru' }}</td>
                </tr>
            </table>

            <p style="margin-top: 14px; text-align: justify; line-height: 1.4;">
                Nama tersebut adalah benar sebagai Pendidik (Guru) pada SMP Muhammadiyah Tonjong yang kami pimpin, terhitung sejak <strong>{{ $tmtFormatted !== '-' ? $tmtFormatted : '....................' }}</strong> dan masih aktif sampai dengan dikeluarkannya surat keterangan ini.
            </p>

            <p style="margin-top: 10px;">
                Demikian surat keterangan ini dibuat dengan sebenarnya, untuk diketahui dan dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <!-- TTD KEPALA SEKOLAH -->
        <div class="ttd-container">
            <div class="ttd-box">
                {{ $pengaturan?->kota ?? 'Tonjong' }}, {{ \Carbon\Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y') }}<br>
                Kepala Sekolah,<br><br><br><br>
                <strong><u>{{ $surat->penandatangan_nama ?? $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.' }}</u></strong><br>
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
                    filename:     'Aktif_Mengajar_{{ str_replace(['/', '\\'], '-', $surat->nomor_surat) }}.pdf',
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