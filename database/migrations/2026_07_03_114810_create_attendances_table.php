<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'alpha'])->default('hadir');
            $table->string('detected_shift')->nullable(); // 'pagi', 'malam', atau 'reguler'
            $table->timestamps();
            
            // Mencegah double attendance dalam satu tanggal per user kecuali untuk security shift malam yang bisa cross-day (nanti ditangani logikanya di service)
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
