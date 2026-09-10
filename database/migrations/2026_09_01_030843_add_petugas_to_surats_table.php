<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('surat_keluars', 'pembuat_surat')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('pembuat_surat')->nullable()->after('tujuan');
            });
        }

        if (!Schema::hasColumn('surat_masuks', 'penerima_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->string('penerima_surat')->nullable()->after('perihal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('surat_keluars', 'pembuat_surat')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->dropColumn('pembuat_surat');
            });
        }

        if (Schema::hasColumn('surat_masuks', 'penerima_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->dropColumn('penerima_surat');
            });
        }
    }
};