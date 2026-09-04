<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('nomor_urut');

            $table->foreignId('jenis_surat_id')
                ->constrained('jenis_surats');

            $table->foreignId('bidang_id')
                ->constrained('bidangs');

            $table->date('tanggal_surat');

            $table->string('perihal');
            $table->text('tujuan')->nullable();

            $table->unsignedSmallInteger('tahun');

            $table->string('nomor_surat')->unique();

            // Tambahan dari SuratMenyurat untuk penanganan dokumen cetak
            $table->string('lampiran')->default('-');
            $table->json('payload_detail')->nullable();

            $table->enum('status', [
                'aktif',
                'dibatalkan',
            ])->default('aktif');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(['nomor_urut', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};