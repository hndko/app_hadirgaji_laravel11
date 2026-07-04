<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Holiday::create([
            'tanggal' => '2024-01-01',
            'keterangan' => 'Tahun Baru',
        ]);

        Holiday::create([
            'tanggal' => '2024-05-01',
            'keterangan' => 'Hari Buruh Internasional',
        ]);
    }
}
