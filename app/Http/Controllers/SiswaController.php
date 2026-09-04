<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa dengan dukungan pencarian & pagination.
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('kelas', 'asc')
                        ->orderBy('nama', 'asc')
                        ->paginate(15)
                        ->withQueryString();

        return view('siswa.index', compact('siswas'));
    }

    /**
     * Menyimpan data siswa baru via Modal Tambah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'kelas'          => 'required|string|max:50',
            'nis'            => 'nullable|string|max:50',
            'nisn'           => 'nullable|string|max:50',
            'nik'            => 'nullable|string|max:100',
            'ttl'            => 'nullable|string|max:255',
            'jenis_kelamin'  => 'nullable|string|max:50',
            'nama_orang_tua' => 'nullable|string|max:255',
            'alamat'         => 'nullable|string',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    /**
     * Memperbarui data siswa via Modal Edit.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'kelas'          => 'required|string|max:50',
            'nis'            => 'nullable|string|max:50',
            'nisn'           => 'nullable|string|max:50',
            'nik'            => 'nullable|string|max:100',
            'ttl'            => 'nullable|string|max:255',
            'jenis_kelamin'  => 'nullable|string|max:50',
            'nama_orang_tua' => 'nullable|string|max:255',
            'alamat'         => 'nullable|string',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil dihapus.');
    }

    /**
     * Import data siswa dari berkas Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file_excel'));
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Endpoint API JSON untuk Select2/Autocomplete pada Form Generator Surat.
     */
    public function getSiswaJson(Request $request)
    {
        $search = $request->get('q');
        $siswas = Siswa::when($search, function($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%");
        })->take(20)->get();

        return response()->json($siswas);
    }
}