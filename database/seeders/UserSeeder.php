<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil jabatan dari tabel jabatans
        $jabatanAdmin = Jabatan::where('nama_jabatan', 'Admin')->first();
        $jabatanKaryawan = Jabatan::where('nama_jabatan', 'Karyawan')->first();
        $jabatanHelper = Jabatan::where('nama_jabatan', 'Helper')->first();
        $jabatanTukang = Jabatan::where('nama_jabatan', 'Tukang')->first();

        // Jika jabatan belum ada di database, buat contoh jabatan
        if (!$jabatanAdmin) {
            $jabatanAdmin = Jabatan::create([
                'nama_jabatan' => 'Admin',
                'gaji_pokok' => 5000000,
                'tunjangan' => 500000,
            ]);
        }

        if (!$jabatanKaryawan) {
            $jabatanKaryawan = Jabatan::create([
                'nama_jabatan' => 'Karyawan',
                'gaji_pokok' => 3000000,
                'tunjangan' => 0,
            ]);
        }

        if (!$jabatanHelper) {
            $jabatanHelper = Jabatan::create([
                'nama_jabatan' => 'Helper',
                'gaji_pokok' => 2500000,
                'tunjangan' => 0,
            ]);
        }

        if (!$jabatanTukang) {
            $jabatanTukang = Jabatan::create([
                'nama_jabatan' => 'Tukang',
                'gaji_pokok' => 3500000,
                'tunjangan' => 0,
            ]);
        }

        // Seeder users dengan jabatan yang diambil dari tabel jabatans
        User::create([
            'nip' => '123456',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'jabatan_id' => $jabatanAdmin->id,
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nip' => '654321',
            'name' => 'Karyawan Jane',
            'email' => 'karyawan01@example.com',
            'jabatan_id' => $jabatanKaryawan->id,
            'role' => 'karyawan',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nip' => '111222',
            'name' => 'Karyawan Alex',
            'email' => 'karyawan02@example.com',
            'jabatan_id' => $jabatanHelper->id,
            'role' => 'karyawan',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nip' => '333444',
            'name' => 'Karyawan Michael',
            'email' => 'karyawan03@example.com',
            'jabatan_id' => $jabatanTukang->id,
            'role' => 'karyawan',
            'password' => Hash::make('password'),
        ]);
    }
}
