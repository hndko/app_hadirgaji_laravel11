<?php

namespace Database\Seeders;

use App\Models\PenaltySetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenaltySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenaltySetting::create([
            'jumlah_denda' => 1000, // Denda Rp1,000 per menit keterlambatan
        ]);
    }
}
