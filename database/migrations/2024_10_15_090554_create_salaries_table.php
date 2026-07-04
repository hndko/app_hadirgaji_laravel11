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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            // Tipe unsignedBigInteger untuk jabatan_id agar cocok dengan id di tabel jabatans
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('year');
            $table->integer('month');
            $table->bigInteger('gaji_pokok')->default(0);
            $table->bigInteger('tunjangan_jabatan')->default(0);
            $table->bigInteger('bonus')->default(0);
            $table->bigInteger('potongan_absensi')->default(0);
            $table->bigInteger('potongan_keterlambatan')->default(0);
            $table->bigInteger('potongan_lainnya')->default(0);
            $table->text('encrypted_salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
