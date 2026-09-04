<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Surat Masuk Tahun {{ $tahun }}</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 10px; 
            color: #000; 
            margin: 0; 
            padding: 0; 
        }
        
        /* KOP SURAT */
        .kop-table { width: 100%; border-collapse: collapse; border: none; margin-bottom: 0; }
        .kop-table td { border: none; padding: 0; vertical-align: middle; }
        .kop-logo-left { width: 15%; text-align: left; }
        .kop-logo-right { width: 15%; text-align: right; }
        .kop-logo-left img, .kop-logo-right img { width: 65px; height: 65px; object-fit: contain; }
        .kop-center { width: 70%; text-align: center; }
        .kop-majelis { font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .kop-pimpinan { font-size: 11px; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
        .kop-sekolah { font-size: 16px; font-weight: 900; text-transform: uppercase; margin-top: 3px; }
        .kop-akreditasi { font-size: 10px; font-weight: bold; margin-top: 2px; }
        .kop-border-double { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin: 3px 0; }
        .kop-alamat { text-align: center; font-size: 9px; font-weight: bold; margin-top: 3px; }
        .kop-kontak { text-align: center; font-size: 9px; font-weight: bold; margin-top: 2px; }
        .kop-border-bottom { border-bottom: 3px solid #000; margin-top: 4px; margin-bottom: 15px; }

        /* JUDUL LAPORAN */
        .title { text-align: center; margin: 10px 0 15px; }
        .title h3 { margin: 0; font-size: 14px; text-decoration: underline; text-transform: uppercase; }
        .title p { margin: 4px 0 0; font-size: 11px; font-weight: bold; }

        /* TABEL DATA */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 5px 4px; text-align: left; font-size: 10px; }
        .data-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }

        /* TANDA TANGAN PRESISI DOMPDF (DIGESER 100PX KE ARAH TENGAH UNTUK STEMPEL) */
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; border: none; }
        .signature-table td { border: none; padding: 0; vertical-align: top; width: 50%; }
        .signature-box-left { width: 220px; text-align: center; margin-left: 100px; }
        .signature-box-right { width: 220px; text-align: center; margin-right: 100px; margin-left: auto; }
        .signature-space { height: 60px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        .signature-nip { font-size: 10px; margin-top: 2px; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo-left">
                @if (file_exists(public_path('images/logo-muhammadiyah.png')))
                    <img src="{{ public_path('images/logo-muhammadiyah.png') }}" alt="Logo Muhammadiyah">
                @endif
            </td>
            <td class="kop-center">
                <div class="kop-majelis">{{ $pengaturan?->nama_majelis ?? 'MAJELIS PENDIDIKAN DASAR, MENENGAH, DAN PENDIDIKAN NONFORMAL' }}</div>
                <div class="kop-pimpinan">{{ $pengaturan?->pimpinan ?? 'PIMPINAN DAERAH MUHAMMADIYAH BREBES' }}</div>
                <div class="kop-sekolah">{{ $pengaturan?->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</div>
                <div class="kop-akreditasi">" TERAKREDITASI A "</div>
            </td>
            <td class="kop-logo-right">
                @if ($pengaturan?->logo && file_exists(public_path('storage/' . $pengaturan->logo)))
                    <img src="{{ public_path('storage/' . $pengaturan->logo) }}" alt="Logo Sekolah">
                @elseif (file_exists(public_path('images/logo-sekolah.png')))
                    <img src="{{ public_path('images/logo-sekolah.png') }}" alt="Logo Sekolah">
                @endif
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 10px; font-weight: bold;">
        <tr>
            <td style="text-align: left; border:none;">NPSN: {{ $pengaturan?->npsn ?? '20326564' }}</td>
            <td style="text-align: right; border:none;">NSS: {{ $pengaturan?->nss ?? '202032906045' }}</td>
        </tr>
    </table>

    <div class="kop-border-double"></div>
    <div class="kop-alamat">
        Alamat: {{ $pengaturan?->alamat ?? 'Jl. Raya Linggapura No. 46, RT 03/RW 03 - Tonjong - Brebes' }} 
        &nbsp;|&nbsp; Kode Pos: {{ $pengaturan?->kode_pos ?? '52271' }} 
        &nbsp;|&nbsp; Telp: {{ $pengaturan?->nomor_hp ?? '(+62) 851-850-333-77' }}
    </div>
    <div class="kop-kontak">
        @if($pengaturan?->email) E-mail: {{ $pengaturan->email }} @endif
        @if($pengaturan?->email && $pengaturan?->website) &nbsp;&nbsp;|&nbsp;&nbsp; @endif
        @if($pengaturan?->website) Website: {{ $pengaturan->website }} @endif
    </div>
    <div class="kop-border-bottom"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="title">
        <h3>REKAPITULASI SURAT MASUK</h3>
        <p>Tahun Anggaran / Periode: {{ $tahun }}</p>
    </div>

    {{-- TABEL ISI --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="17%">Nomor Surat</th>
                <th width="12%">Jenis Surat</th>
                <th width="18%">Pengirim / Instansi</th>
                <th width="9%">Tgl. Surat</th>
                <th width="9%">Tgl. Terima</th>
                <th width="21%">Perihal</th>
                <th width="10%">Penerima</th>
            </tr>
        </thead>
        <tbody>
            @php
                $items = $suratMasuks ?? $surats ?? [];
            @endphp
            @forelse($items as $index => $item)
                @php
                    $rawDateSurat = $item->tanggal_surat ?? $item->tgl_surat ?? null;
                    if ($rawDateSurat instanceof \Carbon\Carbon) {
                        $tglSuratF = $rawDateSurat->format('d/m/Y');
                    } elseif ($rawDateSurat) {
                        $tglSuratF = \Carbon\Carbon::parse($rawDateSurat)->format('d/m/Y');
                    } else {
                        $tglSuratF = '-';
                    }

                    $rawDateTerima = $item->tanggal_diterima ?? $item->tanggal_terima ?? $item->tgl_terima ?? null;
                    if ($rawDateTerima instanceof \Carbon\Carbon) {
                        $tglTerimaF = $rawDateTerima->format('d/m/Y');
                    } elseif ($rawDateTerima) {
                        $tglTerimaF = \Carbon\Carbon::parse($rawDateTerima)->format('d/m/Y');
                    } else {
                        $tglTerimaF = '-';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->nomor_surat }}</strong></td>
                    <td>{{ $item->jenisSurat ? ($item->jenisSurat->kode . ' - ' . $item->jenisSurat->nama) : ($item->jenis_surat ?? '-') }}</td>
                    <td>{{ $item->pengirim ?? $item->asal_surat ?? '-' }}</td>
                    <td class="text-center">{{ $tglSuratF }}</td>
                    <td class="text-center">{{ $tglTerimaF }}</td>
                    <td>{{ $item->perihal }}</td>
                    <td>{{ $item->penerima_surat ?? $item->penerima ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px;">Tidak ada data surat masuk pada tahun {{ $tahun }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-box-left">
                    <div style="visibility: hidden;">{{ $pengaturan?->kota ?? 'Brebes' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 3px;">Kepala Sekolah</div>
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
                    <div>{{ $pengaturan?->kota ?? 'Brebes' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 3px;">Kepala TU</div>
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

</body>
</html>