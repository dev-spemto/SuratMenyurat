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

            // Izinkan nilai negatif untuk data sampel master system (-1, -2, dll)
            $table->integer('nomor_urut');

            $table->foreignId('jenis_surat_id')
                ->constrained('jenis_surats');

            $table->foreignId('bidang_id')
                ->constrained('bidangs');

            // Menyimpan identifier string generator (sppd, und, pbh, pmh, dll)
            $table->string('jenis_surat')->nullable();

            $table->date('tanggal_surat');

            $table->string('perihal');
            $table->text('tujuan')->nullable();

            $table->unsignedSmallInteger('tahun');

            $table->string('nomor_surat')->unique();

            // Tambahan untuk penanganan dokumen cetak dynamic
            $table->string('lampiran')->default('-');
            $table->json('payload_detail')->nullable();

            $table->enum('status', [
                'aktif',
                'dibatalkan',
            ])->default('aktif');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};