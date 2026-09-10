<?php

namespace App\Services;

use App\Models\Bidang;
use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    public function buatSurat(array $data): SuratKeluar
    {
        return DB::transaction(function () use ($data) {
            $tanggal = now()->parse($data['tanggal_surat']);
            $tahun = $tanggal->year;

            // Cari nomor urut terbesar pada tahun tersebut
            $nomorTerakhir = SuratKeluar::where('tahun', $tahun)
                ->lockForUpdate()
                ->max('nomor_urut');

            // Jika belum ada surat sama sekali ATAU max masih 0, mulai dari 1 (001)
            $nomorUrut = ($nomorTerakhir !== null && $nomorTerakhir > 0) ? $nomorTerakhir + 1 : 1;

            $jenisSurat = JenisSurat::findOrFail($data['jenis_surat_id']);
            $bidang = Bidang::findOrFail($data['bidang_id']);

            $nomorSurat = $this->buatNomor(
                $nomorUrut,
                $jenisSurat,
                $bidang,
                $tanggal
            );

            // Pemetaan string jenis_surat dari kode JenisSurat
            $jenisSuratKey = strtolower($jenisSurat->kode ?? 'biasa');

            return SuratKeluar::create([
                'jenis_surat_id' => $data['jenis_surat_id'],
                'jenis_surat'    => $data['jenis_surat'] ?? $jenisSuratKey, // Menjamin kolom jenis_surat terisi
                'bidang_id'      => $data['bidang_id'],
                'nomor_urut'     => $nomorUrut,
                'tahun'          => $tahun,
                'nomor_surat'    => $nomorSurat,
                'tanggal_surat'  => $data['tanggal_surat'],
                'tgl_surat'      => $data['tanggal_surat'], // Menjamin sinkronisasi atribut tanggal
                'perihal'        => $data['perihal'],
                'tujuan'         => $data['tujuan'] ?? $data['tujuan_penerima'] ?? null,
                'tujuan_penerima'=> $data['tujuan_penerima'] ?? $data['tujuan'] ?? null,
                'pembuat_surat'  => $data['pembuat_surat'] ?? null,
                'keterangan'     => $data['keterangan'] ?? null,
                'status'         => 'aktif',
            ]);
        });
    }

    public function updateSurat(
        SuratKeluar $surat,
        array $data
    ): SuratKeluar {
        return DB::transaction(function () use ($surat, $data) {
            $tanggal = now()->parse($data['tanggal_surat']);
            $tahunBaru = $tanggal->year;

            $jenisSurat = JenisSurat::findOrFail(
                $data['jenis_surat_id']
            );

            $bidang = Bidang::findOrFail(
                $data['bidang_id']
            );

            if ((int) $tahunBaru !== (int) $surat->tahun) {
                $nomorTerakhir = SuratKeluar::where('tahun', $tahunBaru)
                    ->lockForUpdate()
                    ->max('nomor_urut');

                $nomorUrut = ($nomorTerakhir !== null && $nomorTerakhir > 0) ? $nomorTerakhir + 1 : 1;
            } else {
                $nomorUrut = $surat->nomor_urut;
            }

            $nomorSurat = $this->buatNomor(
                $nomorUrut,
                $jenisSurat,
                $bidang,
                $tanggal
            );

            $jenisSuratKey = strtolower($jenisSurat->kode ?? $surat->jenis_surat ?? 'biasa');

            $surat->update([
                'jenis_surat_id' => $data['jenis_surat_id'],
                'jenis_surat'    => $data['jenis_surat'] ?? $jenisSuratKey,
                'bidang_id'      => $data['bidang_id'],
                'nomor_urut'     => $nomorUrut,
                'tahun'          => $tahunBaru,
                'nomor_surat'    => $nomorSurat,
                'tanggal_surat'  => $data['tanggal_surat'],
                'tgl_surat'      => $data['tanggal_surat'],
                'perihal'        => $data['perihal'],
                'tujuan'         => $data['tujuan'] ?? $data['tujuan_penerima'] ?? null,
                'tujuan_penerima'=> $data['tujuan_penerima'] ?? $data['tujuan'] ?? null,
                'pembuat_surat'  => $data['pembuat_surat'] ?? null,
                'keterangan'     => $data['keterangan'] ?? null,
            ]);

            return $surat->fresh();
        });
    }

    private function buatNomor(
        int $nomorUrut,
        JenisSurat $jenisSurat,
        Bidang $bidang,
        $tanggal
    ): string {
        return sprintf(
            '%03d/%s/IV.4.AU/%s/%s/%d',
            $nomorUrut,
            $jenisSurat->kode,
            $bidang->kode,
            $this->bulanRomawi($tanggal->month),
            $tanggal->year
        );
    }

    private function bulanRomawi(int $bulan): string
    {
        return [
            1  => 'I',
            2  => 'II',
            3  => 'III',
            4  => 'IV',
            5  => 'V',
            6  => 'VI',
            7  => 'VII',
            8  => 'VIII',
            9  => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ][$bulan];
    }
}