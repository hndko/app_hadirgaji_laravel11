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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Tipe unsignedBigInteger untuk jabatan_id agar cocok dengan id di tabel jabatans
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('tanggal'); // Tanggal absensi
            $table->time('absen_masuk')->nullable(); // Waktu absen masuk
            $table->time('absen_pulang')->nullable(); // Waktu absen pulang
            $table->string('catatan')->nullable(); // Catatan, seperti "Terlambat" atau "Lembur"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
