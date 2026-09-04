<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('surat_masuks', 'jenis_surat_id')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->foreignId('jenis_surat_id')->nullable()->after('pengirim')->constrained('jenis_surats')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_masuks', 'jenis_surat_id')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->dropForeign(['jenis_surat_id']);
                $table->dropColumn('jenis_surat_id');
            });
        }
    }
};