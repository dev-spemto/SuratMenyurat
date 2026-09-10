<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Pengaturan;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::with('jenisSurat');

        // Filter Jenis Surat
        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        // Filter Tahun (berdasarkan tanggal_terima)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_terima', $request->tahun);
        }

        // Filter Pencarian Text (search / cari)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('penerima_surat', 'like', "%{$search}%");
            });
        } elseif ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_surat', 'like', "%{$cari}%")
                  ->orWhere('pengirim', 'like', "%{$cari}%")
                  ->orWhere('perihal', 'like', "%{$cari}%")
                  ->orWhere('penerima_surat', 'like', "%{$cari}%");
            });
        }

        $suratMasuks = $query->latest('id')->paginate(20)->withQueryString();
        $jenisSurats = JenisSurat::where('aktif', true)->orderBy('nama', 'asc')->get();

        return view('surat-masuk.index', compact('suratMasuks', 'jenisSurats'));
    }

    public function create()
    {
        $jenisSurats = JenisSurat::where('aktif', true)->orderBy('nama', 'asc')->get();
        return view('surat-masuk.create', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        // Penanganan fallback otomatis jika name di blade menggunakan tgl_ / penerima
        if (!$request->has('tanggal_surat') && $request->has('tgl_surat')) {
            $request->merge(['tanggal_surat' => $request->input('tgl_surat')]);
        }
        if (!$request->has('tanggal_terima') && $request->has('tgl_terima')) {
            $request->merge(['tanggal_terima' => $request->input('tgl_terima')]);
        }
        if (!$request->has('penerima_surat') && $request->has('penerima')) {
            $request->merge(['penerima_surat' => $request->input('penerima')]);
        }

        $validated = $request->validate([
            'nomor_surat'    => 'required|string|max:255',
            'pengirim'       => 'required|string|max:255',
            'jenis_surat_id' => 'nullable|exists:jenis_surats,id',
            'tanggal_surat'  => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal'        => 'required|string|max:255',
            'penerima_surat' => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'file_surat'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nomor_surat.required'    => 'Nomor Surat Asal wajib diisi.',
            'pengirim.required'       => 'Pengirim / Instansi Asal wajib diisi.',
            'tanggal_surat.required'  => 'Tanggal Terbit Surat wajib diisi.',
            'tanggal_terima.required' => 'Tanggal Diterima Sekolah wajib diisi.',
            'perihal.required'        => 'Perihal Surat wajib diisi.',
            'penerima_surat.required' => 'Penerima / Petugas TU wajib diisi.',
            'file_surat.mimes'        => 'Format file surat harus PDF, JPG, JPEG, atau PNG.',
            'file_surat.max'          => 'Ukuran file surat tidak boleh melebihi 2MB.',
        ]);

        // LOGIKA AUTOMATED NUMBERING UNTUK SURAT MASUK
        $tahunTerima = Carbon::parse($request->tanggal_terima)->year;
        
        $nomorTerakhir = SuratMasuk::whereYear('tanggal_terima', $tahunTerima)
            ->where('nomor_urut', '>', 0) // Mengabaikan nomor urut 0 / sampel
            ->max('nomor_urut');

        $validated['nomor_urut'] = ($nomorTerakhir && $nomorTerakhir > 0) ? ($nomorTerakhir + 1) : 1;
        $validated['tahun'] = $tahunTerima;

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        // Proteksi murni data contoh default master system
        if ($suratMasuk->is_master_sample) {
            return redirect()
                ->route('surat-masuk.show', $suratMasuk)
                ->with('error', 'Surat contoh default master system tidak dapat diubah.');
        }

        $jenisSurats = JenisSurat::where('aktif', true)->orderBy('nama', 'asc')->get();
        return view('surat-masuk.edit', compact('suratMasuk', 'jenisSurats'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        // Proteksi murni data contoh default master system
        if ($suratMasuk->is_master_sample) {
            return redirect()
                ->route('surat-masuk.show', $suratMasuk)
                ->with('error', 'Surat contoh default master system tidak dapat diubah.');
        }

        // Penanganan fallback otomatis
        if (!$request->has('tanggal_surat') && $request->has('tgl_surat')) {
            $request->merge(['tanggal_surat' => $request->input('tgl_surat')]);
        }
        if (!$request->has('tanggal_terima') && $request->has('tgl_terima')) {
            $request->merge(['tanggal_terima' => $request->input('tgl_terima')]);
        }
        if (!$request->has('penerima_surat') && $request->has('penerima')) {
            $request->merge(['penerima_surat' => $request->input('penerima')]);
        }

        $validated = $request->validate([
            'nomor_surat'    => 'required|string|max:255',
            'pengirim'       => 'required|string|max:255',
            'jenis_surat_id' => 'nullable|exists:jenis_surats,id',
            'tanggal_surat'  => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal'        => 'required|string|max:255',
            'penerima_surat' => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'file_surat'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nomor_surat.required'    => 'Nomor Surat Asal wajib diisi.',
            'pengirim.required'       => 'Pengirim / Instansi Asal wajib diisi.',
            'tanggal_surat.required'  => 'Tanggal Terbit Surat wajib diisi.',
            'tanggal_terima.required' => 'Tanggal Diterima Sekolah wajib diisi.',
            'perihal.required'        => 'Perihal Surat wajib diisi.',
            'penerima_surat.required' => 'Penerima / Petugas TU wajib diisi.',
        ]);

        $tahunTerima = Carbon::parse($request->tanggal_terima)->year;
        $validated['tahun'] = $tahunTerima;

        if ($request->hasFile('file_surat')) {
            if ($suratMasuk->file_surat) {
                Storage::disk('public')->delete($suratMasuk->file_surat);
            }
            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        // Proteksi murni data contoh default master system
        if ($suratMasuk->is_master_sample) {
            return back()->with('error', 'Surat contoh default master system tidak dapat dihapus.');
        }

        if ($suratMasuk->file_surat) {
            Storage::disk('public')->delete($suratMasuk->file_surat);
        }

        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }

    public function print(SuratMasuk $suratMasuk)
    {
        $pengaturan = Pengaturan::first();
        return view('surat-masuk.print', compact('suratMasuk', 'pengaturan'));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $suratMasuks = SuratMasuk::with('jenisSurat')
            ->whereYear('tanggal_terima', $tahun)
            ->orderBy('tanggal_terima', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('surat-masuk.export-pdf', compact('suratMasuks', 'tahun', 'pengaturan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Surat_Masuk_Tahun_{$tahun}.pdf");
    }
}