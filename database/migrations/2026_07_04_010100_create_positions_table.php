<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name'); // Nama jabatan/kelas: X-IPA-1, Guru Matematika, Kepala TU, dll
            $table->timestamps();

            $table->unique(['category_id', 'name']); // Unik per kategori
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
