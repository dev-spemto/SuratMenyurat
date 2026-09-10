<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::orderBy('id', 'asc')->get();
        return view('pegawai.index', compact('pegawais'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new PegawaiImport, $request->file('file'));
            return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        return redirect()->route('pegawai.index')->with('error', 'Penambahan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('pegawai.index')->with('error', 'Perubahan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
    }

    public function destroy($id)
    {
        return redirect()->route('pegawai.index')->with('error', 'Penghapusan data manual dinonaktifkan. Silakan gunakan fitur Import Excel.');
    }
}