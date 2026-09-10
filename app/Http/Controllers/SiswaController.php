<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa dengan dukungan sorting dinamis, pencarian, & pagination.
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Parameter Sorting (Default: berdasarkan 'id' ASC untuk menjaga urutan Excel)
        $sortBy  = $request->query('sort_by', 'id');
        $sortDir = strtolower($request->query('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Whitelist kolom yang boleh di-sort demi keamanan
        $allowedSorts = ['id', 'nis', 'nama', 'jenis_kelamin', 'kelas'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy($sortBy, $sortDir)
                        ->paginate(15)
                        ->withQueryString();

        return view('siswa.index', compact('siswas', 'sortBy', 'sortDir'));
    }

    /**
     * Penambahan data manual dinonaktifkan.
     */
    public function store(Request $request)
    {
        return redirect()->route('siswa.index')->with('error', 'Penambahan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
    }

    /**
     * Perubahan data manual dinonaktifkan.
     */
    public function update(Request $request, Siswa $siswa)
    {
        return redirect()->route('siswa.index')->with('error', 'Perubahan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
    }

    /**
     * Penghapusan data manual dinonaktifkan.
     */
    public function destroy(Siswa $siswa)
    {
        return redirect()->route('siswa.index')->with('error', 'Penghapusan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
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
        })->orderBy('id', 'asc')->take(20)->get();

        return response()->json($siswas);
    }
}