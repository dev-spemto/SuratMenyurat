<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('surat_keluars', 'pembuat_surat')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('pembuat_surat')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_keluars', 'pembuat_surat')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->dropColumn('pembuat_surat');
            });
        }
    }
};