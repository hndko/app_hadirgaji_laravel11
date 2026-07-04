<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data jabatan beserta gaji pokok dan tunjangan
        $jabatans = [
            [
                'nama_jabatan' => 'Design',
                'gaji_pokok' => 5000000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Admin',
                'gaji_pokok' => 5000000,
                'tunjangan' => 500000,
            ],
            [
                'nama_jabatan' => 'Helper',
                'gaji_pokok' => 2500000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Tukang',
                'gaji_pokok' => 3000000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Kepala Tukang',
                'gaji_pokok' => 3500000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Korlap',
                'gaji_pokok' => 4000000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Elektrikal',
                'gaji_pokok' => 6000000,
                'tunjangan' => 0,
            ],
            [
                'nama_jabatan' => 'Karyawan',
                'gaji_pokok' => 3000000,
                'tunjangan' => 0,
            ],
        ];

        // Insert data jabatan ke database
        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}
