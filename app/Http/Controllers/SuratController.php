<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\JenisSurat;
use App\Models\Pegawai;
use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuratController extends Controller
{
    /**
     * Helper privat untuk mengecek apakah dokumen merupakan sampel master system (View Only)
     */
    private function isMasterSample(Surat $surat): bool
    {
        return (int)$surat->tahun === 2000 
            || (int)$surat->nomor_urut <= 0 
            || Str::startsWith($surat->nomor_surat, '000/');
    }

    public function index(Request $request)
    {
        $jenis  = $request->query('jenis', $request->query('type'));
        $search = $request->query('search');
        $tahun  = $request->query('tahun');

        $query = Surat::orderBy('id', 'desc');

        // DAFTAR MODUL GENERATOR DOKUMEN KHUSUS
        $jenisKhusus = ['sppd', 'aktif_belajar', 'aktif_mengajar', 'custom'];

        // LOGIKA PEMISAHAN FILTER TABEL:
        if (!empty($jenis)) {
            $query->where('jenis_surat', $jenis);
        } else {
            $query->whereIn('jenis_surat', $jenisKhusus);
        }

        // Filter Pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        // Filter Tahun
        if (!empty($tahun)) {
            $query->where(function ($q) use ($tahun) {
                $q->where('tahun', $tahun)
                  ->orWhereYear('tanggal_surat', $tahun);
            });
        }

        $suratList = $query->paginate(15)->withQueryString();

        // HELPER QUERY UNTUK MENGECEK HANYA SURAT BARU (BUKAN SAMPLE MASTER SYSTEM)
        $nonSampleQuery = function ($q) {
            $q->where('tahun', '!=', 2000)
              ->where('nomor_urut', '>', 0)
              ->where('nomor_surat', 'not like', '000/%');
        };

        // HITUNG REKAP STATISTIK REEL (HANYA SURAT BARU/NON-SAMPLE)
        $totalSppd          = Surat::where('jenis_surat', 'sppd')->where($nonSampleQuery)->count();
        $totalAktifBelajar  = Surat::where('jenis_surat', 'aktif_belajar')->where($nonSampleQuery)->count();
        $totalAktifMengajar = Surat::where('jenis_surat', 'aktif_mengajar')->where($nonSampleQuery)->count();
        $totalCustom        = Surat::where('jenis_surat', 'custom')->where($nonSampleQuery)->count();

        // AKUMULASI TOTAL SURAT GLOBAL MURNI DARI SURAT-SURAT BARU
        $totalSuratGlobal   = $totalSppd + $totalAktifBelajar + $totalAktifMengajar + $totalCustom;

        // Master List Tahun
        $tahunList = Surat::all()
            ->map(function ($surat) {
                return $surat->tahun ?? ($surat->tanggal_surat ? Carbon::parse($surat->tanggal_surat)->format('Y') : null);
            })
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('surat.index', compact(
            'suratList',
            'totalSuratGlobal',
            'totalSppd',
            'totalAktifBelajar',
            'totalAktifMengajar',
            'totalCustom',
            'tahunList',
            'jenis'
        ));
    }

    public function create(Request $request)
    {
        $jenisSurat = $request->query('type', $request->query('jenis', 'sppd'));
        $pegawais = Pegawai::orderBy('id', 'asc')->get();
        $siswas   = Siswa::orderBy('nama', 'asc')->get();

        return view('surat.create', compact('pegawais', 'siswas', 'jenisSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|unique:surat_keluars,nomor_surat',
            'perihal'     => 'required',
            'tgl_surat'   => 'required|date',
        ], [
            'nomor_surat.unique'   => 'Nomor surat sudah pernah digunakan!',
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'perihal.required'     => 'Perihal surat wajib diisi.',
            'tgl_surat.required'   => 'Tanggal surat wajib diisi.',
        ]);

        return DB::transaction(function () use ($request) {
            $jenisSurat = $request->input('jenis_surat', $request->query('jenis', 'sppd'));
            $nomorSuratInput = trim($request->input('nomor_surat'));
            $tglSurat = $request->input('tgl_surat', date('Y-m-d'));
            $tahunSurat = (int) Carbon::parse($tglSurat)->format('Y');

            // Ekstrak nomor urut angka
            preg_match('/^\d+/', $nomorSuratInput, $matches);
            $parsedNumber = isset($matches[0]) ? (int) $matches[0] : 0;

            if ($parsedNumber > 0) {
                $nomorUrutFinal = $parsedNumber;
            } else {
                $lastUrut = Surat::where('jenis_surat', $jenisSurat)
                    ->where(function ($q) use ($tahunSurat) {
                        $q->where('tahun', $tahunSurat)
                          ->orWhereYear('tanggal_surat', $tahunSurat);
                    })->where('nomor_urut', '>', 0)->max('nomor_urut');
                
                $nomorUrutFinal = ($lastUrut && $lastUrut > 0) ? ($lastUrut + 1) : 1;
            }

            $payload = [];
            $tujuanText = $request->input('tujuan_penerima') ?? $request->input('tujuan');

            $penandatanganNama = $request->input('penandatangan_nama', 'IRFAN TUNZILA, S. Ag.');
            $penandatanganJabatan = $request->input('penandatangan_jabatan', 'Kepala Sekolah');

            if ($jenisSurat === 'sppd') {
                $pegawaiIds      = $request->input('pegawai_ids', []);
                $pegawaiJabatans = $request->input('pegawai_jabatans', []);

                $selectedPegawais = Pegawai::whereIn('id', $pegawaiIds)->orderBy('id', 'asc')->get();

                $pegawaiList = [];
                foreach ($selectedPegawais as $p) {
                    $keyInRequest = array_search($p->id, $pegawaiIds);
                    $jabatanSelected = ($keyInRequest !== false && !empty($pegawaiJabatans[$keyInRequest])) 
                                       ? $pegawaiJabatans[$keyInRequest] 
                                       : $p->jabatan;

                    $pegawaiList[] = [
                        'id'         => $p->id,
                        'nama'       => $p->nama,
                        'jabatan'    => $jabatanSelected,
                        'nip'        => $p->nip ?? $p->nuptk_nip ?? $p->nuptk ?? $p->nbm ?? '-',
                        'unit_kerja' => 'SMP MUHAMMADIYAH TONJONG',
                    ];
                }

                $payload = [
                    'maksud_dinas'          => $request->input('maksud_dinas'),
                    'tempat_tujuan'         => $request->input('tempat_tujuan'),
                    'tgl_berangkat'         => $request->input('tgl_berangkat'),
                    'tgl_kembali'           => $request->input('tgl_kembali'),
                    'transportasi'          => $request->input('transportasi', 'Pribadi'),
                    'keterangan'            => $request->input('keterangan'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                    'pegawai_list'          => $pegawaiList,
                ];

                if (empty($tujuanText) && count($pegawaiList) > 0) {
                    $tujuanText = implode(', ', array_column($pegawaiList, 'nama'));
                }
            } elseif ($jenisSurat === 'aktif_belajar') {
                $pInput = $request->input('payload', []);
                $namaSiswa = $pInput['nama_siswa'] ?? $request->input('nama_siswa');

                $payload = [
                    'siswa_id'              => $request->input('siswa_id'),
                    'nama_siswa'            => $namaSiswa,
                    'nis'                   => $pInput['nis'] ?? $request->input('nis'),
                    'nisn'                  => $pInput['nisn'] ?? $request->input('nisn'),
                    'nik'                   => $pInput['nik'] ?? $request->input('nik'),
                    'ttl'                   => $pInput['ttl'] ?? $request->input('ttl'),
                    'jenis_kelamin'         => $pInput['jenis_kelamin'] ?? $request->input('jenis_kelamin', 'Laki-Laki'),
                    'nama_orang_tua'        => $pInput['nama_orang_tua'] ?? $request->input('nama_orang_tua') ?? $request->input('nama_ayah') ?? $request->input('nama_ibu'),
                    'nama_ayah'             => $pInput['nama_ayah'] ?? $request->input('nama_ayah'),
                    'nama_ibu'              => $pInput['nama_ibu'] ?? $request->input('nama_ibu'),
                    'kelas'                 => $pInput['kelas'] ?? $request->input('kelas'),
                    'alamat'                => $pInput['alamat'] ?? $request->input('alamat'),
                    'keperluan'             => $pInput['keperluan'] ?? $request->input('keperluan'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];

                if (empty($tujuanText)) {
                    $tujuanText = $namaSiswa;
                }
            } elseif ($jenisSurat === 'aktif_mengajar') {
                $payload = [
                    'nama_guru'             => $request->input('nama_guru'),
                    'ttl'                   => $request->input('ttl'),
                    'nuptk'                 => $request->input('nuptk'),
                    'nrg'                   => $request->input('nrg'),
                    'nbm'                   => $request->input('nbm'),
                    'nik'                   => $request->input('nik'),
                    'jenis_kelamin'         => $request->input('jenis_kelamin') ?? $request->input('jenis_kelamin_guru', 'Perempuan'),
                    'pendidikan_terakhir'   => $request->input('pendidikan_terakhir'),
                    'tmt'                   => $request->input('tgl_mulai_tugas') ?? $request->input('tmt'),
                    'alamat'                => $request->input('alamat_guru') ?? $request->input('alamat'),
                    'satminkal'             => $request->input('satminkal', 'SMP Muhammadiyah Tonjong (Induk)'),
                    'jabatan_status'        => $request->input('jabatan_status', 'Guru'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];

                if (empty($tujuanText)) {
                    $tujuanText = $request->input('nama_guru');
                }
            } elseif ($jenisSurat === 'custom') {
                $payload = [
                    'isi_surat'             => $request->input('isi_surat'),
                    'waktu_acara'           => $request->input('waktu_acara'),
                    'tempat_acara'          => $request->input('tempat_acara'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];
            }

            // Pemetaan Jenis Surat & Bidang Master Data
            $searchJenis = 'Perjalanan Dinas';
            $searchBidang = 'Pendidikan';

            if ($jenisSurat === 'aktif_belajar') {
                $searchJenis = 'Keterangan Active';
                $searchBidang = 'Kesiswaan';
            } elseif ($jenisSurat === 'aktif_mengajar') {
                $searchJenis = 'Keterangan Active';
                $searchBidang = 'Pendidikan';
            }

            $jenisSuratObj = JenisSurat::where('nama', 'like', "%{$searchJenis}%")
                ->orWhere('kode', 'like', "%{$jenisSurat}%")
                ->first();

            $bidangObj = Bidang::where('nama', 'like', "%{$searchBidang}%")->first();

            Surat::create([
                'nomor_urut'     => $nomorUrutFinal,
                'nomor_surat'    => $nomorSuratInput,
                'jenis_surat'    => $jenisSurat,
                'jenis_surat_id' => $jenisSuratObj?->id ?? 1,
                'bidang_id'      => $request->input('bidang_id', $bidangObj?->id ?? 1),
                'tahun'          => $tahunSurat,
                'perihal'        => $request->input('perihal'),
                'tujuan'         => $tujuanText,
                'tanggal_surat'  => $tglSurat,
                'lampiran'       => $request->input('lampiran', '-'),
                'payload_detail' => $payload,
            ]);

            return redirect()->route('surat.index', ['jenis' => $jenisSurat])
                             ->with('success', 'Dokumen surat berhasil dibuat.');
        });
    }

    public function show(Surat $surat)
    {
        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return redirect()
                ->route('surat.index', ['jenis' => $surat->jenis_surat])
                ->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat diubah.');
        }

        $pegawais = Pegawai::orderBy('id', 'asc')->get();
        $siswas   = Siswa::orderBy('nama', 'asc')->get();

        return view('surat.edit', compact('surat', 'pegawais', 'siswas'));
    }

    public function update(Request $request, Surat $surat)
    {
        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return redirect()
                ->route('surat.index', ['jenis' => $surat->jenis_surat])
                ->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat diubah.');
        }

        $request->validate([
            'nomor_surat' => 'required|unique:surat_keluars,nomor_surat,' . $surat->id,
            'perihal'     => 'required',
            'tgl_surat'   => 'required|date',
        ], [
            'nomor_surat.unique'   => 'Nomor surat sudah pernah digunakan!',
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'perihal.required'     => 'Perihal surat wajib diisi.',
            'tgl_surat.required'   => 'Tanggal surat wajib diisi.',
        ]);

        return DB::transaction(function () use ($request, $surat) {
            $jenisSurat = $surat->jenis_surat;
            $nomorSuratInput = trim($request->input('nomor_surat'));
            $tglSurat = $request->input('tgl_surat', date('Y-m-d'));
            $tahunSurat = (int) Carbon::parse($tglSurat)->format('Y');

            preg_match('/^\d+/', $nomorSuratInput, $matches);
            $parsedNumber = isset($matches[0]) ? (int) $matches[0] : 0;

            if ($parsedNumber > 0) {
                $nomorUrutFinal = $parsedNumber;
            } else {
                $nomorUrutFinal = $surat->nomor_urut ?: 1;
            }

            $payload = [];
            $tujuanText = $request->input('tujuan_penerima') ?? $request->input('tujuan');

            $penandatanganNama = $request->input('penandatangan_nama', $surat->penandatangan_nama ?? 'IRFAN TUNZILA, S. Ag.');
            $penandatanganJabatan = $request->input('penandatangan_jabatan', $surat->penandatangan_jabatan ?? 'Kepala Sekolah');

            if ($jenisSurat === 'sppd') {
                $pegawaiIds      = $request->input('pegawai_ids', []);
                $pegawaiJabatans = $request->input('pegawai_jabatans', []);

                $selectedPegawais = Pegawai::whereIn('id', $pegawaiIds)->orderBy('id', 'asc')->get();

                $pegawaiList = [];
                foreach ($selectedPegawais as $p) {
                    $keyInRequest = array_search($p->id, $pegawaiIds);
                    $jabatanSelected = ($keyInRequest !== false && !empty($pegawaiJabatans[$keyInRequest])) 
                                       ? $pegawaiJabatans[$keyInRequest] 
                                       : $p->jabatan;

                    $pegawaiList[] = [
                        'id'         => $p->id,
                        'nama'       => $p->nama,
                        'jabatan'    => $jabatanSelected,
                        'nip'        => $p->nip ?? $p->nuptk_nip ?? $p->nuptk ?? $p->nbm ?? '-',
                        'unit_kerja' => 'SMP MUHAMMADIYAH TONJONG',
                    ];
                }

                $payload = [
                    'maksud_dinas'          => $request->input('maksud_dinas'),
                    'tempat_tujuan'         => $request->input('tempat_tujuan'),
                    'tgl_berangkat'         => $request->input('tgl_berangkat'),
                    'tgl_kembali'           => $request->input('tgl_kembali'),
                    'transportasi'          => $request->input('transportasi', 'Pribadi'),
                    'keterangan'            => $request->input('keterangan'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                    'pegawai_list'          => $pegawaiList,
                ];

                if (empty($tujuanText) && count($pegawaiList) > 0) {
                    $tujuanText = implode(', ', array_column($pegawaiList, 'nama'));
                }
            } elseif ($jenisSurat === 'aktif_belajar') {
                $pInput = $request->input('payload', []);
                $namaSiswa = $pInput['nama_siswa'] ?? $request->input('nama_siswa');

                $payload = [
                    'siswa_id'              => $request->input('siswa_id'),
                    'nama_siswa'            => $namaSiswa,
                    'nis'                   => $pInput['nis'] ?? $request->input('nis'),
                    'nisn'                  => $pInput['nisn'] ?? $request->input('nisn'),
                    'nik'                   => $pInput['nik'] ?? $request->input('nik'),
                    'ttl'                   => $pInput['ttl'] ?? $request->input('ttl'),
                    'jenis_kelamin'         => $pInput['jenis_kelamin'] ?? $request->input('jenis_kelamin', 'Laki-Laki'),
                    'nama_orang_tua'        => $pInput['nama_orang_tua'] ?? $request->input('nama_orang_tua') ?? $request->input('nama_ayah') ?? $request->input('nama_ibu'),
                    'nama_ayah'             => $pInput['nama_ayah'] ?? $request->input('nama_ayah'),
                    'nama_ibu'              => $pInput['nama_ibu'] ?? $request->input('nama_ibu'),
                    'kelas'                 => $pInput['kelas'] ?? $request->input('kelas'),
                    'alamat'                => $pInput['alamat'] ?? $request->input('alamat'),
                    'keperluan'             => $pInput['keperluan'] ?? $request->input('keperluan'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];

                if (empty($tujuanText)) {
                    $tujuanText = $namaSiswa;
                }
            } elseif ($jenisSurat === 'aktif_mengajar') {
                $payload = [
                    'nama_guru'             => $request->input('nama_guru'),
                    'ttl'                   => $request->input('ttl'),
                    'nuptk'                 => $request->input('nuptk'),
                    'nrg'                   => $request->input('nrg'),
                    'nbm'                   => $request->input('nbm'),
                    'nik'                   => $request->input('nik'),
                    'jenis_kelamin'         => $request->input('jenis_kelamin') ?? $request->input('jenis_kelamin_guru', 'Perempuan'),
                    'pendidikan_terakhir'   => $request->input('pendidikan_terakhir'),
                    'tmt'                   => $request->input('tgl_mulai_tugas') ?? $request->input('tmt'),
                    'alamat'                => $request->input('alamat_guru') ?? $request->input('alamat'),
                    'satminkal'             => $request->input('satminkal', 'SMP Muhammadiyah Tonjong (Induk)'),
                    'jabatan_status'        => $request->input('jabatan_status', 'Guru'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];

                if (empty($tujuanText)) {
                    $tujuanText = $request->input('nama_guru');
                }
            } elseif ($jenisSurat === 'custom') {
                $payload = [
                    'isi_surat'             => $request->input('isi_surat'),
                    'waktu_acara'           => $request->input('waktu_acara'),
                    'tempat_acara'          => $request->input('tempat_acara'),
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                ];
            }

            $searchJenis = 'Perjalanan Dinas';
            $searchBidang = 'Pendidikan';

            if ($jenisSurat === 'aktif_belajar') {
                $searchJenis = 'Keterangan Active';
                $searchBidang = 'Kesiswaan';
            } elseif ($jenisSurat === 'aktif_mengajar') {
                $searchJenis = 'Keterangan Active';
                $searchBidang = 'Pendidikan';
            }

            $jenisSuratObj = JenisSurat::where('nama', 'like', "%{$searchJenis}%")
                ->orWhere('kode', 'like', "%{$jenisSurat}%")
                ->first();

            $bidangObj = Bidang::where('nama', 'like', "%{$searchBidang}%")->first();

            $surat->update([
                'nomor_urut'     => $nomorUrutFinal,
                'nomor_surat'    => $nomorSuratInput,
                'jenis_surat'    => $jenisSurat,
                'jenis_surat_id' => $jenisSuratObj?->id ?? $surat->jenis_surat_id ?? 1,
                'bidang_id'      => $request->input('bidang_id', $bidangObj?->id ?? $surat->bidang_id ?? 1),
                'tahun'          => $tahunSurat,
                'perihal'        => $request->input('perihal'),
                'tujuan'         => $tujuanText,
                'tanggal_surat'  => $tglSurat,
                'lampiran'       => $request->input('lampiran', '-'),
                'payload_detail' => $payload,
            ]);

            return redirect()->route('surat.index', ['jenis' => $jenisSurat])
                             ->with('success', 'Dokumen surat berhasil diperbarui.');
        });
    }

    public function print(Surat $surat)
    {
        $pengaturan = Pengaturan::first();

        return view("surat.print.{$surat->jenis_surat}", compact('surat', 'pengaturan'));
    }

    public function destroy(Surat $surat)
    {
        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return back()->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat dihapus.');
        }

        $jenisSurat = $surat->jenis_surat;
        $surat->delete();

        return redirect()->route('surat.index', ['jenis' => $jenisSurat])
                         ->with('success', 'Dokumen surat berhasil dihapus.');
    }
}