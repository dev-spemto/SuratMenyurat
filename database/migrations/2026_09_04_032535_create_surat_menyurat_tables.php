<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master Data Pegawai / Guru (Skema Lengkap)
        if (!Schema::hasTable('pegawais')) {
            Schema::create('pegawais', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('nuptk')->nullable();
                $table->string('nip')->nullable();
                $table->string('nrg')->nullable();
                $table->string('nbm')->nullable();
                $table->string('nik')->nullable();
                $table->string('ttl')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->string('pendidikan_terakhir')->nullable();
                $table->string('tmt')->nullable();
                $table->text('alamat')->nullable();
                $table->string('satminkal')->nullable();
                $table->string('jabatan')->nullable();
                $table->timestamps();
            });
        }

        // Master Data Siswa
        if (!Schema::hasTable('siswas')) {
            Schema::create('siswas', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('nis')->nullable();
                $table->string('nisn')->nullable();
                $table->string('nik')->nullable();
                $table->string('ttl')->nullable();
                $table->string('kelas')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->string('nama_orang_tua')->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
        Schema::dropIfExists('pegawais');
    }
};