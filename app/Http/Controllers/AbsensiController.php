<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\User; // Tambahkan model User untuk mengambil data karyawan
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman data absensi
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika admin, ambil semua karyawan
        if ($user->role == 'admin') {
            $years = Attendance::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            // Ambil semua karyawan
            $karyawanList = User::where('role', 'karyawan')->get();

            // Karyawan yang dipilih (jika ada)
            $selectedKaryawanId = $request->get('karyawan_id', null);

            // Ambil data absensi untuk karyawan yang dipilih atau semua karyawan jika tidak ada yang dipilih
            $attendancesQuery = Attendance::query();
            if ($selectedKaryawanId) {
                $attendancesQuery->where('user_id', $selectedKaryawanId);
            }
        } else {
            // Jika karyawan, ambil tahun-tahun absensinya saja
            $years = Attendance::where('user_id', $user->id)
                ->selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            $selectedKaryawanId = $user->id;
            $attendancesQuery = Attendance::where('user_id', $user->id);
        }

        // Dapatkan tahun dan bulan dari request atau gunakan default (tahun ini, bulan ini)
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Ambil seluruh tanggal dalam bulan yang dipilih
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // Ambil data attendance berdasarkan tahun dan bulan yang dipilih
        $attendances = $attendancesQuery
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get()
            ->keyBy('tanggal'); // Menggunakan keyBy agar lebih mudah diakses berdasarkan tanggal

        // Ambil data hari libur
        $holidays = Holiday::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->pluck('tanggal');

        $data = [
            'title' => 'Data Absensi',
            'pages' => 'Absensi',
            'master' => null,
            'years' => $years,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'attendances' => $attendances,
            'daysInMonth' => $daysInMonth,
            'holidays' => $holidays,
            'karyawanList' => $user->role == 'admin' ? $karyawanList : null,
            'selectedKaryawanId' => $selectedKaryawanId,
        ];

        return view('dashboard.history_absensi.index', $data);
    }
}
