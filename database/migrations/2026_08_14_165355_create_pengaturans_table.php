<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();

            // Kolom Header & Kop Surat Resmi
            $table->string('nama_majelis')->nullable();
            $table->string('pimpinan')->nullable();
            $table->string('nama_sekolah');
            $table->string('akreditasi')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            
            // Kolom Alamat & Kontak
            $table->string('alamat')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('kota')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Kolom Kepala Sekolah
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->default('-')->nullable();

            // Kolom Logo
            $table->string('logo')->nullable();
            $table->string('logo_kiri')->nullable();
            $table->string('logo_kanan')->nullable();

            // Kolom Sistem & Identitas Aplikasi
            $table->string('nama_aplikasi')
                ->default('Sistem Penomoran Surat');

            $table->string('copyright')
                ->default('Tim Kreatif (Tim IT) SMP Muhammadiyah Tonjong');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};