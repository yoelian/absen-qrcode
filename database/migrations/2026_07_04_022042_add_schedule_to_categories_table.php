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
        Schema::table('categories', function (Blueprint $table) {
            $table->time('target_in_time')->nullable()->after('badge_color')->comment('Batas waktu terlambat masuk');
            $table->time('target_out_time')->nullable()->after('target_in_time')->comment('Batas waktu boleh pulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['target_in_time', 'target_out_time']);
        });
    }
};
