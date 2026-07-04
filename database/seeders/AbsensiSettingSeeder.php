<?php

namespace Database\Seeders;

use App\Models\AbsensiSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AbsensiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AbsensiSetting::create([
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '17:00:00',
            'toleransi_keterlambatan' => 15, // 15 menit
        ]);
    }
}
