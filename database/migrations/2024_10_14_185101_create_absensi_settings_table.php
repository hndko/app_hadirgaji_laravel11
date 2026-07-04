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
        Schema::create('absensi_settings', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk'); // Kolom untuk menyimpan jam masuk
            $table->time('jam_pulang'); // Kolom untuk menyimpan jam pulang
            $table->integer('toleransi_keterlambatan')->default(0); // Toleransi keterlambatan dalam menit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_settings');
    }
};
