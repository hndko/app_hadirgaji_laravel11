<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil minimal 2 user yang sudah terdaftar di database, kecuali user dengan role admin
        $users = User::where('role', '!=', 'admin')->take(2)->get();

        // Loop untuk setiap user
        foreach ($users as $user) {
            // Loop untuk 7 hari ke belakang
            for ($i = 0; $i < 7; $i++) {
                $tanggal = Carbon::now()->subDays($i)->toDateString(); // Tanggal 7 hari kebelakang

                // Randomize jam masuk dan jam pulang dengan variasi waktu
                $jamMasuk = Carbon::createFromTime(8, 0, 0)->addMinutes(rand(0, 60)); // Tambah 0 hingga 60 menit untuk simulasi keterlambatan
                $jamPulang = Carbon::createFromTime(17, 0, 0)->addMinutes(rand(0, 30)); // Tambah 0 hingga 30 menit untuk variasi waktu pulang

                // Buat catatan terlambat atau lembur jika diperlukan
                $catatan = null;
                $toleransiMasuk = Carbon::createFromTime(8, 15, 0); // Toleransi keterlambatan pukul 08:15

                if ($jamMasuk->greaterThan($toleransiMasuk)) { // Jika jam masuk lebih dari 08:15, dianggap terlambat
                    $catatan = 'Terlambat';
                }

                // Cek apakah hari ini adalah akhir pekan atau hari libur
                $isHoliday = now()->subDays($i)->isWeekend();
                if ($isHoliday) {
                    $catatan = 'Lembur';
                }

                // Insert data absensi ke dalam tabel attendances
                Attendance::create([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                    'absen_masuk' => $jamMasuk->toTimeString(),
                    'absen_pulang' => $jamPulang->toTimeString(),
                    'catatan' => $catatan,
                ]);
            }
        }
    }
}
