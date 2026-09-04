<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\JenisSurat;
use App\Models\Pengaturan;
use App\Models\SuratKeluar;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        // FILTER UTAMA: Hanya ambil Arsip Surat Keluar Murni (Excluding SPPD, Aktif Belajar, dll)
        $query = SuratKeluar::arsipKeluar()->with(['jenisSurat', 'bidang']);

        // Filter Jenis Surat
        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter Pencarian Text (search / cari)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('pembuat_surat', 'like', "%{$search}%");
            });
        } elseif ($request->filled('cari')) { // Fallback jika menggunakan parameter 'cari'
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_surat', 'like', "%{$cari}%")
                  ->orWhere('perihal', 'like', "%{$cari}%")
                  ->orWhere('tujuan', 'like', "%{$cari}%")
                  ->orWhere('pembuat_surat', 'like', "%{$cari}%");
            });
        }

        $surats = $query->latest('id')->paginate(20)->withQueryString();
        $jenisSurats = JenisSurat::where('aktif', true)->orderBy('nama', 'asc')->get();

        return view('surat-keluar.index', compact('surats', 'jenisSurats'));
    }

    public function create()
    {
        $jenisSurats = JenisSurat::where('aktif', true)
            ->orderBy('nama', 'asc')
            ->get();

        $bidangs = Bidang::where('aktif', true)
            ->orderBy('kode', 'asc')
            ->get();

        return view('surat-keluar.create', compact(
            'jenisSurats',
            'bidangs'
        ));
    }

    public function store(
        Request $request,
        NomorSuratService $nomorSuratService
    ) {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'bidang_id'      => 'required|exists:bidangs,id',
            'tanggal_surat'  => 'required|date',
            'perihal'        => 'required|string|max:255',
            'tujuan'         => 'nullable|string',
            'pembuat_surat'  => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
        ]);

        $surat = $nomorSuratService->buatSurat($validated);

        return redirect()
            ->route('surat-keluar.show', $surat)
            ->with('success', 'Surat berhasil dibuat.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'bidang']);

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function print(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'bidang']);

        $pengaturan = Pengaturan::first();

        return view(
            'surat-keluar.print',
            compact('suratKeluar', 'pengaturan')
        );
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Pastikan Export PDF Rekap Surat Keluar juga mengecualikan SPPD & generator lainnya
        $suratKeluars = SuratKeluar::arsipKeluar()
            ->with(['jenisSurat', 'bidang'])
            ->whereYear('tanggal_surat', $tahun)
            ->orderBy('tanggal_surat', 'asc')
            ->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('surat-keluar.export-pdf', compact('suratKeluars', 'tahun', 'pengaturan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Surat_Keluar_Tahun_{$tahun}.pdf");
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        // Proteksi khusus hanya untuk data master contoh (Tahun 2000 dengan urut 0)
        if ((int)$suratKeluar->tahun === 2000 && (int)$suratKeluar->nomor_urut === 0) {
            return redirect()
                ->route('surat-keluar.show', $suratKeluar)
                ->with('error', 'Surat contoh default master system tidak dapat diubah.');
        }

        $jenisSurats = JenisSurat::where('aktif', true)
            ->orderBy('nama', 'asc')
            ->get();

        $bidangs = Bidang::where('aktif', true)
            ->orderBy('kode', 'asc')
            ->get();

        return view('surat-keluar.edit', compact(
            'suratKeluar',
            'jenisSurats',
            'bidangs'
        ));
    }

    public function update(
        Request $request,
        SuratKeluar $suratKeluar,
        NomorSuratService $nomorSuratService
    ) {
        // Proteksi khusus hanya untuk data master contoh (Tahun 2000 dengan urut 0)
        if ((int)$suratKeluar->tahun === 2000 && (int)$suratKeluar->nomor_urut === 0) {
            return redirect()
                ->route('surat-keluar.show', $suratKeluar)
                ->with('error', 'Surat contoh default master system tidak dapat diubah.');
        }

        $validated = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'bidang_id'      => ['required', 'exists:bidangs,id'],
            'tanggal_surat'  => ['required', 'date'],
            'perihal'        => ['required', 'string', 'max:255'],
            'tujuan'         => ['nullable', 'string'],
            'pembuat_surat'  => ['required', 'string', 'max:255'],
            'keterangan'     => ['nullable', 'string'],
        ]);

        try {
            $nomorSuratService->updateSurat(
                $suratKeluar,
                $validated
            );
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['tanggal_surat' => $e->getMessage()]);
        }

        return redirect()
            ->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat berhasil diperbarui.');
    }

    public function batalkan(
        Request $request,
        SuratKeluar $suratKeluar
    ) {
        // Proteksi khusus hanya untuk data master contoh (Tahun 2000 dengan urut 0)
        if ((int)$suratKeluar->tahun === 2000 && (int)$suratKeluar->nomor_urut === 0) {
            return back()->with('error', 'Surat contoh default master system tidak dapat dibatalkan.');
        }

        if ($suratKeluar->status === 'dibatalkan') {
            return back()->with('error', 'Surat sudah dibatalkan.');
        }

        $validated = $request->validate([
            'keterangan' => ['required', 'string', 'max:500'],
        ]);

        $suratKeluar->update([
            'status' => 'dibatalkan',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()
            ->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat berhasil dibatalkan.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        // Proteksi khusus hanya untuk data master contoh (Tahun 2000 dengan urut 0)
        if ((int)$suratKeluar->tahun === 2000 && (int)$suratKeluar->nomor_urut === 0) {
            return back()->with('error', 'Surat contoh default master system tidak dapat dihapus.');
        }

        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }
}