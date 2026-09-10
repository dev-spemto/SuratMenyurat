<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dikosongkan karena seluruh kolom identitas sudah didefinisikan secara lengkap 
        // pada file migration utama create_pengaturans_table
    }

    public function down(): void
    {
        // Dikosongkan agar tidak menghapus kolom utama saat rollback
    }
};