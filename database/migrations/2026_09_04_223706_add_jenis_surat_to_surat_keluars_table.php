<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_keluars', 'jenis_surat')) {
                $table->string('jenis_surat')->nullable()->after('nomor_surat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            if (Schema::hasColumn('surat_keluars', 'jenis_surat')) {
                $table->dropColumn('jenis_surat');
            }
        });
    }
};