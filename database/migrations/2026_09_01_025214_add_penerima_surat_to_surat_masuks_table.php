<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('surat_masuks', 'penerima_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->string('penerima_surat')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_masuks', 'penerima_surat')) {
            Schema::table('surat_masuks', function (Blueprint $table) {
                $table->dropColumn('penerima_surat');
            });
        }
    }
};