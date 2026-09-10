<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_masuks', 'nomor_urut')) {
                $table->integer('nomor_urut')->default(0)->nullable()->after('id');
            }

            if (!Schema::hasColumn('surat_masuks', 'tahun')) {
                $table->year('tahun')->nullable()->after('nomor_urut');
            }

            if (!Schema::hasColumn('surat_masuks', 'jenis_surat_id')) {
                $table->foreignId('jenis_surat_id')->nullable()->after('pengirim')->constrained('jenis_surats')->nullOnDelete();
            }

            if (!Schema::hasColumn('surat_masuks', 'penerima_surat')) {
                $table->string('penerima_surat')->nullable()->after('tanggal_terima');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            if (Schema::hasColumn('surat_masuks', 'jenis_surat_id')) {
                $table->dropForeign(['jenis_surat_id']);
                $table->dropColumn('jenis_surat_id');
            }

            $columnsToDrop = array_filter(['nomor_urut', 'tahun', 'penerima_surat'], function ($col) {
                return Schema::hasColumn('surat_masuks', $col);
            });

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};