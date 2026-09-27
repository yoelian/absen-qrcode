<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama kategori: Siswa, Guru, TU, Security, CS
            $table->string('code')->unique(); // Kode kategori untuk prefix id, e.g., SIS, GUR, TU, SEC, CS
            $table->string('badge_color')->default('primary'); // Kelas Bootstrap: primary, success, warning, info, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
