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
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    /**
     * Helper privat untuk mengecek apakah dokumen merupakan sampel master system (View Only)
     */
    private function isMasterSample(Surat $surat): bool
    {
        return $surat->is_master_sample || Str::startsWith($surat->nomor_surat, '000/');
    }

    /**
     * Helper privat untuk memproses array tembusan agar rapi dan selalu diakhiri 'Pertinggal (Arsip)'
     */
    private function processTembusan(array $rawTembusan): array
    {
        $clean = array_values(array_filter(array_map('trim', $rawTembusan), function($item) {
            return !empty($item);
        }));

        // Hapus jika sudah ada entri 'Pertinggal (Arsip)' agar tidak ganda
        $keyArsip = array_search('Pertinggal (Arsip)', $clean);
        if ($keyArsip !== false) {
            unset($clean[$keyArsip]);
            $clean = array_values($clean);
        }

        // Selalu tambahkan 'Pertinggal (Arsip)' di posisi paling akhir
        $clean[] = 'Pertinggal (Arsip)';

        return $clean;
    }

    public function index(Request $request)
    {
        $jenis  = $request->query('jenis', $request->query('type'));
        $search = $request->query('search');
        $tahun  = $request->query('tahun');

        $query = Surat::orderBy('id', 'desc');

        // DAFTAR MODUL GENERATOR DOKUMEN KHUSUS (SPPD, Aktif Belajar, Aktif Mengajar, UND, PBH, PMH, Custom)
        $jenisKhusus = ['sppd', 'aktif_belajar', 'aktif_mengajar', 'und', 'pbh', 'pmh', 'custom'];

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
        $totalUndangan      = Surat::where('jenis_surat', 'und')->where($nonSampleQuery)->count();
        $totalPemberitahuan = Surat::where('jenis_surat', 'pbh')->where($nonSampleQuery)->count();
        $totalPermohonan    = Surat::where('jenis_surat', 'pmh')->where($nonSampleQuery)->count();
        $totalCustom        = Surat::where('jenis_surat', 'custom')->where($nonSampleQuery)->count();

        // AKUMULASI TOTAL SURAT GLOBAL MURNI DARI SURAT-SURAT BARU
        $totalSuratGlobal   = $totalSppd + $totalAktifBelajar + $totalAktifMengajar + $totalUndangan + $totalPemberitahuan + $totalPermohonan + $totalCustom;

        // Master List Tahun (Kompatibel SQLite & MySQL)
        $tahunList = Surat::selectRaw("DISTINCT COALESCE(tahun, strftime('%Y', tanggal_surat)) as tahun_val")
            ->orderBy('tahun_val', 'desc')
            ->pluck('tahun_val')
            ->filter()
            ->values();

        return view('surat.index', compact(
            'suratList',
            'totalSuratGlobal',
            'totalSppd',
            'totalAktifBelajar',
            'totalAktifMengajar',
            'totalUndangan',
            'totalPemberitahuan',
            'totalPermohonan',
            'totalCustom',
            'tahunList',
            'jenis'
        ));
    }

    public function create(Request $request)
    {
        $jenisSurat = strtolower($request->query('type', $request->query('jenis', 'sppd')));
        $pegawais = Pegawai::orderBy('id', 'asc')->get();
        $siswas   = Siswa::orderBy('nama', 'asc')->get();

        return view('surat.create', compact('pegawais', 'siswas', 'jenisSurat'));
    }

    /**
     * Menampilkan form pembuatan Surat Undangan, Pemberitahuan, & Permohonan
     * Mengarahkan ke file view universal 'surat.create'
     */
    public function createUndangan(Request $request)
    {
        $jenisSurat = strtolower($request->query('type', $request->query('jenis', 'und')));
        $pegawais  = Pegawai::orderBy('nama', 'asc')->get();
        $siswas    = Siswa::orderBy('nama', 'asc')->get();

        return view('surat.create', compact('pegawais', 'siswas', 'jenisSurat'));
    }

    /**
     * Handler khusus simpan dari Rute /surat/undangan (POST)
     */
    public function storeUndangan(Request $request)
    {
        $jenisSurat = strtolower($request->input('jenis_kode', $request->input('jenis_surat', 'und')));
        $request->merge(['jenis_surat' => $jenisSurat]);

        return $this->store($request);
    }

    public function store(Request $request)
    {
        $tableSurat = (new Surat())->getTable();

        $request->validate([
            'nomor_surat' => "required|unique:{$tableSurat},nomor_surat",
            'perihal'     => 'required',
            'tgl_surat'   => 'required|date',
        ], [
            'nomor_surat.unique'   => 'Nomor surat sudah pernah digunakan!',
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'perihal.required'     => 'Perihal surat wajib diisi.',
            'tgl_surat.required'   => 'Tanggal surat wajib diisi.',
        ]);

        return DB::transaction(function () use ($request) {
            $jenisSurat = strtolower($request->input('jenis_surat', $request->query('jenis', 'sppd')));
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
            $tujuanText = $request->input('tujuan_penerima') ?? $request->input('tujuan') ?? $request->input('penerima');

            $penandatanganNama = $request->input('penandatangan_nama', 'IRFAN TUNZILA, S. Ag.');
            $penandatanganJabatan = $request->input('penandatangan_jabatan', 'Kepala Sekolah');
            $penandatanganNip = $request->input('penandatangan_nip', '-');

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
            } elseif (in_array($jenisSurat, ['und', 'pbh', 'pmh'])) {
                $rawTembusan = $request->input('tembusan', $request->input('tembusan_list', []));
                if (is_string($rawTembusan)) {
                    $rawTembusan = explode(',', $rawTembusan);
                }

                $payload = [
                    'penerima'              => $tujuanText,
                    'tempat_penerima'       => $request->input('tempat_penerima', 'di - Tempat'),
                    'salam_pembuka'         => $request->input('salam_pembuka') ?: "Assalamu'alaikum Wr. Wb.",
                    'paragraf_pembuka'      => $request->input('paragraf_pembuka'),
                    'hari_tanggal'          => $request->input('hari_tanggal'),
                    'waktu'                 => $request->input('waktu', $request->input('waktu_acara')),
                    'waktu_acara'           => $request->input('waktu_acara', $request->input('waktu')),
                    'tempat_acara'          => $request->input('tempat_acara'),
                    'agenda'                => $request->input('agenda', $request->input('agenda_acara')),
                    'agenda_acara'          => $request->input('agenda_acara', $request->input('agenda')),
                    'keterangan'            => $request->input('keterangan', $request->input('keterangan_acara')),
                    'keterangan_acara'      => $request->input('keterangan_acara', $request->input('keterangan')),
                    'paragraf_penutup'      => $request->input('paragraf_penutup'),
                    'salam_penutup'         => $request->input('salam_penutup') ?: "Wassalamu'alaikum Wr. Wb.",
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                    'penandatangan_nip'     => $penandatanganNip,
                    'tembusan_list'         => $this->processTembusan((array) $rawTembusan),
                ];
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
            } elseif ($jenisSurat === 'und') {
                $searchJenis = 'Undangan';
                $searchBidang = 'Pendidikan';
            } elseif ($jenisSurat === 'pbh') {
                $searchJenis = 'Pemberitahuan';
                $searchBidang = 'Pendidikan';
            } elseif ($jenisSurat === 'pmh') {
                $searchJenis = 'Permohonan';
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

            $targetRoute = \Route::has('surat-keluar.index') ? 'surat-keluar.index' : 'surat.index';

            return redirect()->route($targetRoute, ['jenis' => $jenisSurat])
                             ->with('success', 'Dokumen surat berhasil dibuat.');
        });
    }

    public function show(Surat $surat)
    {
        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        $targetRoute = \Route::has('surat-keluar.index') ? 'surat-keluar.index' : 'surat.index';

        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return redirect()
                ->route($targetRoute, ['jenis' => $surat->jenis_surat])
                ->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat diubah.');
        }

        $pegawais = Pegawai::orderBy('id', 'asc')->get();
        $siswas   = Siswa::orderBy('nama', 'asc')->get();

        return view('surat.edit', compact('surat', 'pegawais', 'siswas'));
    }

    public function update(Request $request, Surat $surat)
    {
        $targetRoute = \Route::has('surat-keluar.index') ? 'surat-keluar.index' : 'surat.index';

        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return redirect()
                ->route($targetRoute, ['jenis' => $surat->jenis_surat])
                ->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat diubah.');
        }

        $tableSurat = (new Surat())->getTable();

        $request->validate([
            'nomor_surat' => "required|unique:{$tableSurat},nomor_surat," . $surat->id,
            'perihal'     => 'required',
            'tgl_surat'   => 'required|date',
        ], [
            'nomor_surat.unique'   => 'Nomor surat sudah pernah digunakan!',
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'perihal.required'     => 'Perihal surat wajib diisi.',
            'tgl_surat.required'   => 'Tanggal surat wajib diisi.',
        ]);

        return DB::transaction(function () use ($request, $surat, $targetRoute) {
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
            $tujuanText = $request->input('tujuan_penerima') ?? $request->input('tujuan') ?? $request->input('penerima');

            $penandatanganNama = $request->input('penandatangan_nama', $surat->penandatangan_nama ?? 'IRFAN TUNZILA, S. Ag.');
            $penandatanganJabatan = $request->input('penandatangan_jabatan', $surat->penandatangan_jabatan ?? 'Kepala Sekolah');
            $penandatanganNip = $request->input('penandatangan_nip', $surat->payload_detail['penandatangan_nip'] ?? '-');

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
            } elseif (in_array($jenisSurat, ['und', 'pbh', 'pmh'])) {
                $rawTembusan = $request->input('tembusan', $request->input('tembusan_list', []));
                if (is_string($rawTembusan)) {
                    $rawTembusan = explode(',', $rawTembusan);
                }

                $payload = [
                    'penerima'              => $tujuanText,
                    'tempat_penerima'       => $request->input('tempat_penerima', 'di - Tempat'),
                    'salam_pembuka'         => $request->input('salam_pembuka') ?: "Assalamu'alaikum Wr. Wb.",
                    'paragraf_pembuka'      => $request->input('paragraf_pembuka'),
                    'hari_tanggal'          => $request->input('hari_tanggal'),
                    'waktu'                 => $request->input('waktu', $request->input('waktu_acara')),
                    'waktu_acara'           => $request->input('waktu_acara', $request->input('waktu')),
                    'tempat_acara'          => $request->input('tempat_acara'),
                    'agenda'                => $request->input('agenda', $request->input('agenda_acara')),
                    'agenda_acara'          => $request->input('agenda_acara', $request->input('agenda')),
                    'keterangan'            => $request->input('keterangan', $request->input('keterangan_acara')),
                    'keterangan_acara'      => $request->input('keterangan_acara', $request->input('keterangan')),
                    'paragraf_penutup'      => $request->input('paragraf_penutup'),
                    'salam_penutup'         => $request->input('salam_penutup') ?: "Wassalamu'alaikum Wr. Wb.",
                    'penandatangan_nama'    => $penandatanganNama,
                    'penandatangan_jabatan' => $penandatanganJabatan,
                    'penandatangan_nip'     => $penandatanganNip,
                    'tembusan_list'         => $this->processTembusan((array) $rawTembusan),
                ];
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
            } elseif ($jenisSurat === 'und') {
                $searchJenis = 'Undangan';
                $searchBidang = 'Pendidikan';
            } elseif ($jenisSurat === 'pbh') {
                $searchJenis = 'Pemberitahuan';
                $searchBidang = 'Pendidikan';
            } elseif ($jenisSurat === 'pmh') {
                $searchJenis = 'Permohonan';
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

            return redirect()->route($targetRoute, ['jenis' => $jenisSurat])
                             ->with('success', 'Dokumen surat berhasil diperbarui.');
        });
    }

    public function print(Surat $surat)
    {
        $pengaturan = Pengaturan::first();

        // Panggilan View Dinamis ke masing-masing file: und.blade.php, pbh.blade.php, atau pmh.blade.php
        $viewPath = "surat.print.{$surat->jenis_surat}";

        // Proteksi Fallback ke custom jika file blade spesifik tidak ditemukan
        if (!view()->exists($viewPath)) {
            $viewPath = 'surat.print.custom';
        }

        return view($viewPath, compact('surat', 'pengaturan'));
    }

    /**
     * Method baru untuk Export / Cetak Dokumen ke PDF (Sama seperti sistem SPPD)
     */
    public function exportPdf(Surat $surat)
    {
        $pengaturan = Pengaturan::first();

        $viewPath = "surat.print.{$surat->jenis_surat}";

        if (!view()->exists($viewPath)) {
            $viewPath = 'surat.print.custom';
        }

        $pdf = Pdf::loadView($viewPath, compact('surat', 'pengaturan'))
                  ->setPaper('A4', 'portrait');

        $filename = "Surat_" . strtoupper($surat->jenis_surat) . "_" . str_replace('/', '_', $surat->nomor_surat) . ".pdf";

        return $pdf->stream($filename);
    }

    public function destroy(Surat $surat)
    {
        $targetRoute = \Route::has('surat-keluar.index') ? 'surat-keluar.index' : 'surat.index';

        // PROTEKSI SAMPLE MASTER SYSTEM (VIEW ONLY)
        if ($this->isMasterSample($surat)) {
            return back()->with('error', 'Dokumen contoh default master system bersifat view-only dan tidak dapat dihapus.');
        }

        $jenisSurat = $surat->jenis_surat;
        $surat->delete();

        return redirect()->route($targetRoute, ['jenis' => $jenisSurat])
                         ->with('success', 'Dokumen surat berhasil dihapus.');
    }
}