<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $suratKeluar = $suratKeluar ?? $surat;
        $rawDate = $suratKeluar->tanggal_surat ?? $suratKeluar->tgl_surat ?? null;
        if ($rawDate instanceof \Carbon\Carbon) {
            $tglFormatted = $rawDate->translatedFormat('d F Y');
        } elseif ($rawDate) {
            $tglFormatted = \Carbon\Carbon::parse($rawDate)->translatedFormat('d F Y');
        } else {
            $tglFormatted = '-';
        }
    @endphp
    <title>Bukti Registrasi Surat Keluar - {{ $suratKeluar->nomor_surat }}</title>

    {{-- CDN PDF.JS UNTUK MERENDER SEMUA HALAMAN PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #e5e7eb; font-family: Arial, sans-serif; color: #111827; }

        .toolbar {
            padding: 15px; background: #166534; display: flex; justify-content: center; gap: 10px;
        }

        .button {
            padding: 10px 16px; border: 0; border-radius: 7px; font-size: 13px; font-weight: bold; cursor: pointer; text-decoration: none;
        }
        .button-print { background: white; color: #166534; }
        .button-back { background: #14532d; color: white; border: 1px solid #4ade80; }

        .paper {
            width: 210mm; min-height: 297mm; margin: 25px auto; padding: 15mm 20mm; background: white; box-shadow: 0 4px 20px rgba(0, 0, 0, .12); display: flex; flex-direction: column; justify-content: space-between;
        }

        /* STYLING KOP SURAT */
        .kop-container { width: 100%; font-family: Arial, sans-serif; color: #000; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-logo-left { width: 15%; text-align: left; vertical-align: middle; }
        .kop-logo-right { width: 15%; text-align: right; vertical-align: middle; }
        .kop-logo-left img, .kop-logo-right img { max-width: 85px; max-height: 85px; object-fit: contain; }
        .kop-center { width: 70%; text-align: center; vertical-align: middle; }
        .kop-majelis { font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .kop-pimpinan { font-size: 12px; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
        .kop-sekolah { font-size: 18px; font-weight: 900; text-transform: uppercase; margin-top: 3px; }
        .kop-akreditasi { font-size: 11px; font-weight: bold; margin-top: 2px; }
        .kop-npsn-nss { display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; margin-top: 8px; padding: 0 5px; }
        .kop-border-double { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 3px; margin: 3px 0; }
        .kop-alamat, .kop-kontak { text-align: center; font-size: 10px; font-weight: bold; margin-top: 3px; }
        .kop-border-bottom { border-bottom: 3px solid #000; margin-top: 4px; }

        /* KONTEN SURAT */
        .title { text-align: center; margin: 25px 0 10px; }
        .title h2 { margin: 0; font-size: 18px; text-decoration: underline; text-transform: uppercase; }
        .title p { margin: 5px 0 0; font-size: 13px; font-weight: bold; }

        .content { margin-top: 20px; line-height: 1.6; font-size: 13px; }

        /* TABEL RINCIAN ISI SURAT */
        .detail-table {
            width: calc(100% - 30px);
            border-collapse: collapse;
            margin: 20px 0 20px 30px;
        }

        .detail-table td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 13px;
        }

        .detail-label {
            width: 160px;
            font-weight: bold;
        }

        .detail-colon {
            width: 20px;
            text-align: left;
        }

        .body-text { margin-top: 20px; text-align: justify; }

        /* TANDA TANGAN */
        .signature-container {
            margin-top: 40px;
            padding: 0 40px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            vertical-align: top;
            width: 50%;
        }
        .signature-box-left {
            text-align: center;
            width: 240px;
            margin-right: auto;
        }
        .signature-box-right {
            text-align: center;
            width: 240px;
            margin-left: auto;
        }
        .signature-space { height: 70px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        .signature-nip { font-size: 12px; margin-top: 2px; }

        .footer { margin-top: auto; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }

        /* HALAMAN LAMPIRAN (PAGE BREAK) */
        .attachment-page {
            page-break-before: always;
            padding-top: 10mm;
        }
        .attachment-header {
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .attachment-header h3 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .attachment-header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #4b5563;
        }
        .attachment-body {
            text-align: center;
            margin-top: 15px;
            width: 100%;
        }
        .attachment-img {
            max-width: 100%;
            max-height: 220mm;
            border: 1px solid #d1d5db;
            padding: 5px;
            object-fit: contain;
        }
        canvas.attachment-canvas {
            max-width: 100%;
            max-height: 220mm;
            border: 1px solid #d1d5db;
            padding: 3px;
            object-fit: contain;
        }

        @media print {
            @page { size: A4; margin: 0; }
            body { background: white; }
            .toolbar { display: none; }
            .paper { margin: 0; width: 210mm; min-height: 297mm; box-shadow: none; }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <a href="{{ route('surat-keluar.show', $suratKeluar->id) }}" class="button button-back">← Kembali</a>
        <button onclick="window.print()" class="button button-print">🖨 Cetak Bukti Registrasi</button>
    </div>

    {{-- HALAMAN 1: LEMBAR REGISTRASI & PENGARSIPAN --}}
    <div class="paper">
        <div>
            {{-- KOP SEKOLAH --}}
            <div class="kop-container">
                <table class="kop-table">
                    <tr>
                        <td class="kop-logo-left">
                            @if ($pengaturan?->logo_kiri)
                                <img src="{{ asset('storage/' . $pengaturan->logo_kiri) }}" alt="Logo Muhammadiyah">
                            @else
                                <img src="{{ asset('images/logo-muhammadiyah.png') }}" alt="Logo Muhammadiyah">
                            @endif
                        </td>
                        <td class="kop-center">
                            <div class="kop-majelis">{{ $pengaturan?->nama_majelis ?? 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL' }}</div>
                            <div class="kop-pimpinan">{{ $pengaturan?->pimpinan ?? 'PIMPINAN DAERAH MUHAMMADIYAH BREBES' }}</div>
                            <div class="kop-sekolah">{{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</div>
                            <div class="kop-akreditasi">" {{ $pengaturan?->akreditasi ?? 'TERAKREDITASI A' }} "</div>
                        </td>
                        <td class="kop-logo-right">
                            @if ($pengaturan?->logo)
                                <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo Sekolah">
                            @elseif ($pengaturan?->logo_kanan)
                                <img src="{{ asset('storage/' . $pengaturan->logo_kanan) }}" alt="Logo Sekolah">
                            @else
                                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah">
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="kop-npsn-nss">
                    <span>NPSN: {{ $pengaturan?->npsn ?? '20326564' }}</span>
                    <span>NSS: {{ $pengaturan?->nss ?? '202032906045' }}</span>
                </div>
                <div class="kop-border-double"></div>
                <div class="kop-alamat">
                    Alamat: {{ $pengaturan?->alamat ?? 'Jl. Raya Linggapura No. 46, RT 03/RW 03 - Tonjong - Brebes' }}
                    &#9993; {{ $pengaturan?->kode_pos ?? '52271' }}
                    &#9742; {{ $pengaturan?->nomor_hp ?? '(+62) 851-850-333-77' }}
                </div>
                <div class="kop-kontak">
                    @if($pengaturan?->email) E-mail: {{ $pengaturan->email }} @endif
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    @if($pengaturan?->website) Website: {{ $pengaturan->website }} @endif
                </div>
                <div class="kop-border-bottom"></div>
            </div>

            {{-- JUDUL --}}
            <div class="title">
                <h2>LEMBAR REGISTRASI SURAT KELUAR</h2>
                <p>Nomor Agenda / Surat: {{ $suratKeluar->nomor_surat }}</p>
            </div>

            {{-- CONTENT --}}
            <div class="content">
                <p>
                    Dokumen ini diterbitkan sebagai <strong>Bukti Registrasi dan Lembar Pengesahan Arsip Resmi</strong> untuk pencatatan riwayat surat keluar pada sistem administrasi penomoran surat sekolah. Rincian data administratif adalah sebagai berikut:
                </p>

                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Nomor Surat</td>
                        <td class="detail-colon">:</td>
                        <td><strong>{{ $suratKeluar->nomor_surat }}</strong></td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jenis Surat</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $suratKeluar->jenisSurat ? ($suratKeluar->jenisSurat->kode . ' - ' . $suratKeluar->jenisSurat->nama) : ($suratKeluar->jenis_surat ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Bidang</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $suratKeluar->bidang ? ($suratKeluar->bidang->kode . ' - ' . $suratKeluar->bidang->nama) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Tanggal Terbit</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $tglFormatted }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Perihal</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $suratKeluar->perihal }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Tujuan / Penerima</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $suratKeluar->tujuan ?? $suratKeluar->tujuan_penerima ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Pembuat Surat</td>
                        <td class="detail-colon">:</td>
                        <td>{{ $suratKeluar->pembuat_surat ?? '-' }}</td>
                    </tr>
                </table>

                <div class="body-text">
                    <p>
                        Demikian bukti registrasi ini dicetak sebagai dokumen verifikasi, validasi, dan pengarsipan resmi pada instansi sekolah. Bersama dengan lembar ini, terlampir salinan dokumen pendukung berdasarkan surat yang telah terdaftar dalam sistem.
                    </p>

                    @if ($suratKeluar->keterangan)
                        <p>
                            <strong>Catatan Tambahan:</strong><br>
                            {{ $suratKeluar->keterangan }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- TANDA TANGAN --}}
            <div class="signature-container">
                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="signature-box-left">
                                <div style="visibility: hidden;">{{ $pengaturan?->kota ?? 'Tonjong' }}, {{ $tglFormatted }}</div>
                                <div style="margin-top: 5px;">Kepala Sekolah</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">
                                    {{ $pengaturan?->nama_kepala_sekolah ?? 'IRFAN TUNZILA, S. Ag.' }}
                                </div>
                                <div class="signature-nip">
                                    NIP. {{ $pengaturan?->nip_kepala_sekolah ?: '-' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="signature-box-right">
                                <div>{{ $pengaturan?->kota ?? 'Tonjong' }}, {{ $tglFormatted }}</div>
                                <div style="margin-top: 5px;">Kepala TU</div>
                                <div class="signature-space"></div>
                                <div class="signature-name">
                                    Samiaji Aditya Nugroho Gifa
                                </div>
                                <div class="signature-nip">
                                    NIP. -
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            Sistem Informasi Pengarsipan Surat · {{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}
        </div>
    </div>

    {{-- HALAMAN LAMPIRAN (GAMBAR ATAU MULTI-HALAMAN PDF) --}}
    @if ($suratKeluar->file_surat)
        @php
            $extension = strtolower(pathinfo($suratKeluar->file_surat, PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            $isPdf = $extension === 'pdf';
        @endphp

        {{-- JIKA FILE BERBENTUK GAMBAR --}}
        @if ($isImage)
            <div class="paper attachment-page">
                <div>
                    <div class="attachment-header">
                        <h3>LAMPIRAN DOKUMEN SURAT KELUAR</h3>
                        <p>Lampiran berkas fisik/scan resmi untuk Nomor Surat: {{ $suratKeluar->nomor_surat }}</p>
                    </div>

                    <div class="attachment-body">
                        <img src="{{ asset('storage/' . $suratKeluar->file_surat) }}" alt="Lampiran Surat Keluar" class="attachment-img">
                    </div>
                </div>

                <div class="footer">
                    Lampiran Dokumen Resmi · {{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}
                </div>
            </div>

        {{-- JIKA FILE BERBENTUK PDF (DIPROSES DENGAN PDF.JS UNTUK MULTI-HALAMAN) --}}
        @elseif ($isPdf)
            <div id="pdf-container"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const pdfUrl = "{{ asset('storage/' . $suratKeluar->file_surat) }}";

                    // Inisialisasi Worker PDF.js
                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

                    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                        const container = document.getElementById('pdf-container');

                        // Loop setiap halaman PDF
                        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                            (function(num) {
                                pdf.getPage(num).then(function(page) {
                                    const scale = 2; // Resolusi tinggi untuk cetak
                                    const viewport = page.getViewport({ scale: scale });

                                    // Buat wrapper A4 untuk setiap halaman PDF
                                    const pageDiv = document.createElement('div');
                                    pageDiv.className = 'paper attachment-page';

                                    pageDiv.innerHTML = `
                                        <div>
                                            <div class="attachment-header">
                                                <h3>LAMPIRAN DOKUMEN SURAT KELUAR ${pdf.numPages > 1 ? `(Halaman ${num} dari ${pdf.numPages})` : ''}</h3>
                                                <p>Lampiran berkas fisik/scan resmi untuk Nomor Surat: {{ $suratKeluar->nomor_surat }}</p>
                                            </div>
                                            <div class="attachment-body" id="canvas-wrapper-${num}"></div>
                                        </div>
                                        <div class="footer">
                                            Lampiran Dokumen Resmi · {{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}
                                        </div>
                                    `;

                                    container.appendChild(pageDiv);

                                    // Buat Canvas HTML5 untuk render gambar PDF
                                    const canvas = document.createElement('canvas');
                                    canvas.className = 'attachment-canvas';
                                    const context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    document.getElementById(`canvas-wrapper-${num}`).appendChild(canvas);

                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                });
                            })(pageNum);
                        }
                    }).catch(function(error) {
                        console.error('Gagal memuat PDF:', error);
                    });
                });
            </script>
        @endif
    @endif

</body>

</html>