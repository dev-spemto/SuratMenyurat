<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::first();

        return view('pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_majelis'        => ['nullable', 'string', 'max:255'],
            'pimpinan'            => ['nullable', 'string', 'max:255'],
            'nama_sekolah'        => ['required', 'string', 'max:255'],
            'akreditasi'          => ['nullable', 'string', 'max:100'],
            'npsn'                => ['nullable', 'string', 'max:50'],
            'nss'                 => ['nullable', 'string', 'max:50'],
            'alamat'              => ['nullable', 'string'],
            'kode_pos'            => ['nullable', 'string', 'max:20'],
            'nomor_hp'            => ['nullable', 'string', 'max:30'],
            'email'               => ['nullable', 'email', 'max:255'],
            'website'             => ['nullable', 'string', 'max:255'],
            'nama_kepala_sekolah' => ['nullable', 'string', 'max:255'],
            'nip_kepala_sekolah'  => ['nullable', 'string', 'max:100'],
            'logo_kiri'           => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'logo_kanan'          => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $pengaturan = Pengaturan::first();

        // Handle upload Logo Muhammadiyah (Posisi Kiri Kop)
        if ($request->hasFile('logo_kiri')) {
            if ($pengaturan?->logo_kiri) {
                Storage::disk('public')->delete($pengaturan->logo_kiri);
            }

            $validated['logo_kiri'] = $request
                ->file('logo_kiri')
                ->store('logo', 'public');
        }

        // Handle upload Logo Sekolah (Posisi Kanan Kop)
        if ($request->hasFile('logo_kanan')) {
            if ($pengaturan?->logo_kanan) {
                Storage::disk('public')->delete($pengaturan->logo_kanan);
            }

            $validated['logo_kanan'] = $request
                ->file('logo_kanan')
                ->store('logo', 'public');
        }

        Pengaturan::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return back()->with(
            'success',
            'Identitas sekolah berhasil disimpan.'
        );
    }
}