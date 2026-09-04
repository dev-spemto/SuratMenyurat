<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar pegawai dan modal form.
     */
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama', 'asc')->get();
        return view('pegawai.index', compact('pegawais'));
    }

    /**
     * Menyimpan data pegawai baru dari Modal Tambah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                => 'required|string|max:255',
            'nik'                 => 'nullable|string|max:50',
            'nuptk'               => 'nullable|string|max:50',
            'nip'                 => 'nullable|string|max:50',
            'nrg'                 => 'nullable|string|max:50',
            'nbm'                 => 'nullable|string|max:50',
            'ttl'                 => 'nullable|string|max:255',
            'jenis_kelamin'       => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tmt'                 => 'nullable|string|max:100',
            'jabatan'             => 'nullable|string|max:255',
            'satminkal'           => 'nullable|string|max:255',
            'alamat'              => 'nullable|string',
        ]);

        // Duplikasi ke field legacy jika diperlukan
        $validated['nuptk_nip'] = $validated['nuptk'] ?? $validated['nip'] ?? '-';

        Pegawai::create($validated);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    /**
     * Memperbarui data pegawai dari Modal Edit.
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama'                => 'required|string|max:255',
            'nik'                 => 'nullable|string|max:50',
            'nuptk'               => 'nullable|string|max:50',
            'nip'                 => 'nullable|string|max:50',
            'nrg'                 => 'nullable|string|max:50',
            'nbm'                 => 'nullable|string|max:50',
            'ttl'                 => 'nullable|string|max:255',
            'jenis_kelamin'       => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tmt'                 => 'nullable|string|max:100',
            'jabatan'             => 'nullable|string|max:255',
            'satminkal'           => 'nullable|string|max:255',
            'alamat'              => 'nullable|string',
        ]);

        $validated['nuptk_nip'] = $validated['nuptk'] ?? $validated['nip'] ?? '-';

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus!');
    }
}