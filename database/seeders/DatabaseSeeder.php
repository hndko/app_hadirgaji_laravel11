<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\HolidaySeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\AbsensiSettingSeeder;
use Database\Seeders\PenaltySettingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JabatanSeeder::class,
            UserSeeder::class,
            AbsensiSettingSeeder::class,
            HolidaySeeder::class,
            PenaltySettingSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
