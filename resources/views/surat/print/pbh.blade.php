<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pemberitahuan - {{ $surat->nomor_surat }}</title>
    
    <!-- LIBRARY HTML2PDF FOR CLIENT-SIDE EXPORT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        @page {
            size: A4;
            margin: 1.2cm 1.8cm 1.5cm 1.8cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.15;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* KOP SURAT PRESISI FISIK (SPASI TUNGGAL / 1.0) */
        .kop-surat {
            width: 100%;
            margin-bottom: 10px;
            line-height: 1.0;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-logo-left {
            width: 75px;
            text-align: left;
            vertical-align: middle;
        }
        .kop-logo-right {
            width: 75px;
            text-align: right;
            vertical-align: middle;
        }
        .kop-logo-left img,
        .kop-logo-right img {
            width: 68px;
            height: auto;
            display: block;
        }
        .kop-text-container {
            text-align: center;
            vertical-align: middle;
        }
        .kop-header-top {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        .kop-header-mid {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-header-main {
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2px 0;
        }
        .kop-akreditasi {
            font-size: 8.5pt;
            font-weight: bold;
            text-decoration: none;
        }
        .kop-npsn-nss {
            font-size: 8.5pt;
            font-weight: bold;
            width: 100%;
            margin-top: 4px;
            border-collapse: collapse;
        }
        .kop-alamat {
            font-size: 8pt;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            margin-top: 3px;
            text-align: center;
            line-height: 1.2;
        }
        .garis-double {
            border-bottom: 3px double #000;
            margin-top: 2px;
            margin-bottom: 12px;
        }

        /* METADATA NOMOR & TANGGAL SURAT (SPASI 1.15) */
        .meta-container {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            line-height: 1.15;
        }
        .meta-container td {
            vertical-align: top;
        }

        /* WRAPPER KONTEN UTAMA (INDENTASI SEJAJAR DENGAN ISI LAMPIRAN) */
        .content-indented-wrapper {
            margin-left: 77px;
        }

        /* TUJUAN PENERIMA SURAT (TANPA UNDERLINE) */
        .penerima-block {
            margin-top: 8px;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        .penerima-block .di-line {
            margin-left: 0px;
        }
        .penerima-block .tempat-line {
            margin-left: 40px;
            text-decoration: none;
        }

        /* ISI PARAGRAF SURAT (SPASI 1.5 RESMI) */
        p.paragraf-isi {
            line-height: 1.5;
            margin: 0 0 10px 0;
            text-align: justify;
        }

        .salam-text {
            font-style: italic;
            text-decoration: none;
            line-height: 1.5;
            margin-bottom: 8px;
            margin-top: 8px;
        }

        /* TABEL RINCIAN PEMBERITAHUAN */
        .table-data {
            margin: 6px 0 12px 0px;
            border-collapse: collapse;
            width: 100%;
            line-height: 1.2;
        }
        .table-data td {
            padding: 2px 4px;
            vertical-align: top;
        }

        /* TANDA TANGAN (RATA KANAN MENTOK PINGGIR KANAN HALAMAN) */
        .ttd-container {
            width: 100%;
            margin-top: 15px;
            text-align: right;
            line-height: 1.2;
            page-break-inside: avoid;
        }
        .ttd-block {
            display: inline-block;
            text-align: left;
            min-width: 220px;
        }
        .ttd-space {
            height: 50px;
        }

        /* TEMBUSAN SURAT */
        .tembusan {
            margin-top: 15px;
            font-size: 9.5pt;
            line-height: 1.2;
            page-break-inside: avoid;
        }
        .tembusan ol {
            margin: 2px 0 0 18px;
            padding: 0;
        }

        .font-bold {
            font-weight: bold;
        }

        /* MEDIA PRINT CONTROL */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOMBOL AKSI CETAK (NO PRINT) -->
    <div class="no-print" style="position: fixed; top: 15px; right: 15px; z-index: 9999; background: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
            Tutup
        </button>
    </div>

    @php 
        $p = $surat->payload_detail ?? [];
        if (is_string($p)) {
            $p = json_decode($p, true) ?? [];
        }

        $tglSurat = $surat->tgl_surat ?? $surat->tanggal_surat ?? date('Y-m-d');

        // NORMALISASI & FILTER TEMBUSAN
        $tembusanRaw = $p['tembusan_list'] ?? $p['tembusan'] ?? [];
        if (is_string($tembusanRaw)) {
            $tembusanRaw = explode(',', $tembusanRaw);
        }

        $cleanList = array_values(array_filter(array_map(function($item) {
            return preg_replace('/^\d+\.\s*/', '', trim($item));
        }, (array)$tembusanRaw), function($item) {
            $lower = strtolower($item);
            return !empty($item) && $lower !== 'pertinggal (arsip)' && $lower !== 'pertinggal';
        }));

        $cleanList[] = "Pertinggal (Arsip)";

        // CHECK SALAM PEMBUKA & PENUTUP
        $salamPembuka = isset($p['salam_pembuka']) ? trim($p['salam_pembuka']) : "Assalamualaikum Wr. Wb.";
        $salamPenutup = isset($p['salam_penutup']) ? trim($p['salam_penutup']) : "Wassalamu'alaikum Wr. Wb.";

        // FIX PATH LOGO DENGAN FALLBACK
        $logoMuhammadiyah = file_exists(public_path('images/logo-muhammadiyah.png')) ? asset('images/logo-muhammadiyah.png') : asset('images/logo_muhammadiyah.png');
        
        $logoSekolah = asset('images/logo.png');
        if (file_exists(public_path('images/logo.png'))) {
            $logoSekolah = asset('images/logo.png');
        } elseif (file_exists(public_path('images/logo-sekolah.png'))) {
            $logoSekolah = asset('images/logo-sekolah.png');
        } elseif (file_exists(public_path('images/logo_sekolah.png'))) {
            $logoSekolah = asset('images/logo_sekolah.png');
        }

        // SANITASI TEKS TEMPAT PENERIMA
        $tempatPenerima = $p['tempat_penerima'] ?? 'Tempat';
        $tempatPenerima = preg_replace('/^(di\s*-\s*|di\s+)/i', '', trim($tempatPenerima));
    @endphp

    <!-- PEMBUNGKUS UTAMA DOKUMEN UNTUK EXPORT PDF -->
    <div id="document-render" style="padding: 10px; background: #fff;">

        <!-- KOP SURAT RESMI MUHAMMADIYAH TONJONG -->
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo-left">
                        <img src="{{ $logoMuhammadiyah }}" alt="Logo Muhammadiyah">
                    </td>
                    <td class="kop-text-container">
                        <div class="kop-header-top">MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL</div>
                        <div class="kop-header-mid">PIMPINAN DAERAH MUHAMMADIYAH BREBES</div>
                        <div class="kop-header-main">SMP MUHAMMADIYAH TONJONG</div>
                        <div class="kop-akreditasi">" TERAKREDITASI A "</div>
                    </td>
                    <td class="kop-logo-right">
                        <img src="{{ $logoSekolah }}" alt="Logo Sekolah">
                    </td>
                </tr>
            </table>

            <table class="kop-npsn-nss">
                <tr>
                    <td style="width: 50%; text-align: left;">NPSN: 20326564</td>
                    <td style="width: 50%; text-align: right;">NSS: 202032906045</td>
                </tr>
            </table>

            <div class="kop-alamat">
                Alamat: Jl. Raya Linggapura No. 46 – Tonjong – Brebes ✉ 52271 ☎ (+62) 851-850-333-77<br>
                E-mail: <u>smpmuhitonjong@gmail.com</u> Website: <u>https://smpmuhtonjong.sch.id</u>
            </div>
            <div class="garis-double"></div>
        </div>

        <!-- METADATA SURAT & TANGGAL (RATA KANAN ATAS) -->
        <table class="meta-container">
            <tr>
                <td style="width: 55%;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <td style="width: 65px;">Nomor</td>
                            <td style="width: 12px;">:</td>
                            <td>{{ $surat->nomor_surat }}</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td class="font-bold">{{ strtoupper($surat->perihal ?? $p['perihal'] ?? 'PEMBERITAHUAN') }}</td>
                        </tr>
                        <tr>
                            <td>Lamp</td>
                            <td>:</td>
                            <td>{{ $surat->lampiran ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 45%; text-align: right;">
                    Tonjong, {{ \Carbon\Carbon::parse($tglSurat)->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- WRAPPER INDENTASI (KEPADA YTH SEJAJAR LURUS DENGAN ISI LAMPIRAN) -->
        <div class="content-indented-wrapper">
            
            <!-- TUJUAN PENERIMA SURAT (TANPA UNDERLINE) -->
            <div class="penerima-block">
                <div>Kepada Yth.</div>
                <div class="font-bold">{{ strtoupper($surat->tujuan_penerima ?? $surat->tujuan ?? $p['penerima'] ?? $p['tujuan_detail'] ?? '-') }}</div>
                <div class="di-line">Di-</div>
                <div class="tempat-line">{{ $tempatPenerima }}</div>
            </div>

            <!-- SALAM PEMBUKA -->
            @if(!empty($salamPembuka))
                <p class="salam-text">{{ $salamPembuka }}</p>
            @endif

            <!-- PARAGRAF PEMBUKA (SPASI 1.5) -->
            @if(!empty(trim($p['paragraf_pembuka'] ?? '')))
                <p class="paragraf-isi">
                    {{ $p['paragraf_pembuka'] }}
                </p>
            @else
                <p class="paragraf-isi">
                    Ba'da salam, teriring doa semoga Bapak/Ibu senantiasa dalam keadaan sehat wal'afiat serta sukses dalam menjalankan aktivitas sehari-hari.
                </p>
                <p class="paragraf-isi">
                    Disampaikan dengan hormat, melalui surat ini kami memberitahukan hal-hal sebagai berikut:
                </p>
            @endif

            <!-- DETAIL ACARA / PEMBERITAHUAN -->
            @if(!empty($p['hari_tanggal']) || !empty($p['waktu']) || !empty($p['waktu_acara']) || !empty($p['tempat_acara']) || !empty($p['agenda']) || !empty($p['agenda_acara']))
            <table class="table-data">
                @if(!empty($p['hari_tanggal']))
                <tr>
                    <td style="width: 110px;">Hari, Tanggal</td>
                    <td style="width: 12px;">:</td>
                    <td>{{ $p['hari_tanggal'] }}</td>
                </tr>
                @endif
                
                @if(!empty($p['waktu_acara']) || !empty($p['waktu']))
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>{{ $p['waktu_acara'] ?? $p['waktu'] }}</td>
                </tr>
                @endif

                @if(!empty($p['tempat_acara']))
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td>{{ $p['tempat_acara'] }}</td>
                </tr>
                @endif

                @if(!empty($p['agenda_acara']) || !empty($p['agenda']))
                <tr>
                    <td>Agenda</td>
                    <td>:</td>
                    <td class="font-bold">{{ $p['agenda_acara'] ?? $p['agenda'] }}</td>
                </tr>
                @endif

                @if(!empty($p['keterangan_acara']) || !empty($p['keterangan']))
                <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{{ $p['keterangan_acara'] ?? $p['keterangan'] }}</td>
                </tr>
                @endif
            </table>
            @endif

            <!-- PARAGRAF PENUTUP (SPASI 1.5) -->
            @if(!empty(trim($p['paragraf_penutup'] ?? '')))
                <p class="paragraf-isi">
                    {{ $p['paragraf_penutup'] }}
                </p>
            @else
                <p class="paragraf-isi">
                    Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
                </p>
            @endif

            <!-- SALAM PENUTUP -->
            @if(!empty($salamPenutup))
                <p class="salam-text">{{ $salamPenutup }}</p>
            @endif

        </div> <!-- END WRAPPER INDENTASI -->

        <!-- TANDA TANGAN (RATA KANAN MENTOK PINGGIR KANAN HALAMAN) -->
        <div class="ttd-container">
            <div class="ttd-block">
                <div>{{ $p['penandatangan_jabatan'] ?? 'Kepala Sekolah' }},</div>
                
                <div class="ttd-space">
                    @if(!empty($pengaturan->ttd_image))
                        <img src="{{ asset('storage/' . $pengaturan->ttd_image) }}" style="height: 50px; margin-top: 2px;" alt="TTD">
                    @endif
                </div>
                
                <div class="font-bold" style="text-decoration: underline;">
                    {{ $surat->penandatangan_nama ?? $p['penandatangan_nama'] ?? ($pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.') }}
                </div>
                <div>NBM. {{ $p['penandatangan_nip'] ?? ($pengaturan?->nip_kepala_sekolah ?? '1032 933') }}</div>
            </div>
        </div>

        <!-- TEMBUSAN SURAT -->
        @if(count($cleanList) > 0)
        <div class="tembusan">
            <div class="font-bold"><u>Tembusan:</u></div>
            <ol>
                @foreach($cleanList as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ol>
        </div>
        @endif

    </div> <!-- END DOCUMENT-RENDER -->

    <!-- SKRIP OTOMATIS DOWNLOAD PDF -->
    @if(request('download') === 'pdf')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const element = document.getElementById('document-render');
                const opt = {
                    margin:       [0.4, 0.4, 0.4, 0.4],
                    filename:     'PBH_{{ str_replace(["/", "\\"], "-", $surat->nomor_surat) }}.pdf',
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