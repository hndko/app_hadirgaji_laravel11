<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Salary;
use App\Models\Holiday;
use App\Models\Attendance;
use App\Models\AbsensiSetting;
use App\Models\PenaltySetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use \Mpdf\Mpdf as PDF;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Fetch all employee records with their salaries
        $karyawanList = User::where('role', 'karyawan')->get();
        $salaries = Salary::where('year', $year)
            ->where('month', $month)
            ->get()
            ->keyBy('user_id'); // Key by user ID for easier lookup

        $data = [
            'title' => 'Penggajian',
            'pages' => 'Penggajian',
            'master' => null, // if necessary, pass additional master data
            'karyawanList' => $karyawanList,
            'salaries' => $salaries,
            'year' => $year,
            'month' => $month,
        ];

        return view('dashboard.penggajian.index', $data);
    }

    /**
     * Show the form for creating a new salary.
     */
    public function create(Request $request)
    {
        $year = $request->get('tahun', now()->year);
        $month = $request->get('bulan', now()->month);
        $userId = $request->get('user_id');

        // Retrieve user
        $user = User::findOrFail($userId);

        // Get salary details from the user's position (jabatan)
        $gaji_pokok = $user->jabatan->gaji_pokok;
        $tunjangan_jabatan = $user->jabatan->tunjangan;

        // Retrieve absensi settings and penalty settings
        $absensiSetting = AbsensiSetting::first();
        $toleranceMinutes = $absensiSetting->toleransi_keterlambatan;
        $penalty = PenaltySetting::first();
        $dendaPerMenit = $penalty->jumlah_denda;

        // Retrieve holidays in the current month
        $holidays = Holiday::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->pluck('tanggal');

        // Calculate total working days excluding weekends and holidays
        $totalHariKerja = Carbon::createFromDate($year, $month, 1)
            ->daysInMonth;

        $totalHariKerja = collect(range(1, $totalHariKerja))->reduce(function ($carry, $day) use ($year, $month, $holidays) {
            $date = Carbon::createFromDate($year, $month, $day);
            if (!$date->isWeekend() && !$holidays->contains($date->toDateString())) {
                return $carry + 1;
            }
            return $carry;
        }, 0);

        // Calculate total attendance (days where user attended)
        $totalKehadiran = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereNotNull('absen_masuk')
            ->whereNotIn('tanggal', $holidays)
            ->where(function ($query) {
                $query->whereRaw('WEEKDAY(tanggal) != 5') // Exclude Saturdays
                    ->whereRaw('WEEKDAY(tanggal) != 6'); // Exclude Sundays
            })
            ->count();

        // Calculate total lateness (only for 'Terlambat' attendance records)
        $totalKeterlambatan = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('catatan', 'Terlambat')
            ->whereNotIn('tanggal', $holidays)
            ->where(function ($query) {
                $query->whereRaw('WEEKDAY(tanggal) != 5') // Exclude Saturdays
                    ->whereRaw('WEEKDAY(tanggal) != 6'); // Exclude Sundays
            })
            ->count();

        // Calculate total lateness in minutes for the user
        $totalKeterlambatanMenit = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereNotNull('absen_masuk')
            ->get()
            ->reduce(function ($carry, $attendance) use ($absensiSetting, $toleranceMinutes) {
                $scheduledTime = $absensiSetting->jam_masuk;
                $scheduledDateTime = Carbon::parse($attendance->tanggal . ' ' . $scheduledTime);
                $absenMasukTime = Carbon::parse($attendance->tanggal . ' ' . $attendance->absen_masuk);

                $latenessMinutes = $scheduledDateTime->diffInMinutes($absenMasukTime, false);
                if ($latenessMinutes > $toleranceMinutes) {
                    return $carry + ($latenessMinutes - $toleranceMinutes);
                }
                return $carry;
            }, 0);

        // Calculate the lateness deduction
        $potonganKeterlambatan = $totalKeterlambatanMenit * $dendaPerMenit;

        // Calculate total absences (days where the user didn't attend)
        $totalTidakHadir = $totalHariKerja - $totalKehadiran;

        // Calculate Potongan Absensi
        $potonganAbsensi = round(($gaji_pokok / $totalHariKerja) * $totalTidakHadir);


        // Prepare data to pass to the view
        $data = [
            'title' => 'Create Salary',
            'pages' => 'Penggajian',
            'master' => null, // if needed
            'year' => $year,
            'month' => $month,
            'user' => $user,
            'gaji_pokok' => $gaji_pokok,
            'tunjangan_jabatan' => $tunjangan_jabatan,
            'potonganKeterlambatan' => $potonganKeterlambatan,
            'potonganAbsensi' => $potonganAbsensi,
            'totalHariKerja' => $totalHariKerja,
            'totalKehadiran' => $totalKehadiran,
            'totalKeterlambatan' => $totalKeterlambatan,
            'totalTidakHadir' => $totalTidakHadir,
        ];

        return view('dashboard.penggajian.create', $data);
    }

    /**
     * Store a newly created salary in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gaji_pokok' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'potongan_absensi' => 'nullable|numeric',
            'potongan_keterlambatan' => 'nullable|numeric',
            'potongan_lainnya' => 'nullable|numeric',
        ]);

        $totalGaji = $request->gaji_pokok + $request->tunjangan_jabatan + $request->bonus
            - $request->potongan_absensi - $request->potongan_keterlambatan - $request->potongan_lainnya;

        $encryptedSalary = Crypt::encrypt($totalGaji);

        // Store salary
        Salary::create([
            'user_id' => $request->user_id,
            'year' => $request->year,
            'month' => $request->month,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan_jabatan' => $request->tunjangan_jabatan,
            'bonus' => $request->bonus,
            'potongan_absensi' => $request->potongan_absensi,
            'potongan_keterlambatan' => $request->potongan_keterlambatan,
            'potongan_lainnya' => $request->potongan_lainnya,
            'encrypted_salary' => $encryptedSalary,
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Data gaji berhasil disimpan.');
    }

    public function edit(Request $request, $id)
    {
        $year = $request->get('tahun', now()->year);
        $month = $request->get('bulan', now()->month);
        $userId = $request->get('user_id');

        // Retrieve user
        $user = User::findOrFail($userId);

        // Retrieve existing salary record
        $salary = Salary::findOrFail($id);

        // Get salary details from the user's position (jabatan)
        $gaji_pokok = $user->jabatan->gaji_pokok;
        $tunjangan_jabatan = $user->jabatan->tunjangan;

        // Retrieve absensi settings and penalty settings
        $absensiSetting = AbsensiSetting::first();
        $toleranceMinutes = $absensiSetting->toleransi_keterlambatan;
        $penalty = PenaltySetting::first();
        $dendaPerMenit = $penalty->jumlah_denda;

        // Retrieve holidays in the current month
        $holidays = Holiday::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->pluck('tanggal');

        // Calculate total working days excluding weekends and holidays
        $totalHariKerja = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $totalHariKerja = collect(range(1, $totalHariKerja))->reduce(function ($carry, $day) use ($year, $month, $holidays) {
            $date = Carbon::createFromDate($year, $month, $day);
            if (!$date->isWeekend() && !$holidays->contains($date->toDateString())) {
                return $carry + 1;
            }
            return $carry;
        }, 0);

        // Calculate total attendance (days where user attended)
        $totalKehadiran = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereNotNull('absen_masuk')
            ->whereNotIn('tanggal', $holidays)
            ->where(function ($query) {
                $query->whereRaw('WEEKDAY(tanggal) != 5') // Exclude Saturdays
                    ->whereRaw('WEEKDAY(tanggal) != 6'); // Exclude Sundays
            })
            ->count();

        // Calculate total lateness (only for 'Terlambat' attendance records)
        $totalKeterlambatan = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('catatan', 'Terlambat')
            ->whereNotIn('tanggal', $holidays)
            ->where(function ($query) {
                $query->whereRaw('WEEKDAY(tanggal) != 5') // Exclude Saturdays
                    ->whereRaw('WEEKDAY(tanggal) != 6'); // Exclude Sundays
            })
            ->count();

        // Calculate total lateness in minutes for the user
        $totalKeterlambatanMenit = Attendance::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereNotNull('absen_masuk')
            ->get()
            ->reduce(function ($carry, $attendance) use ($absensiSetting, $toleranceMinutes) {
                $scheduledTime = $absensiSetting->jam_masuk;
                $scheduledDateTime = Carbon::parse($attendance->tanggal . ' ' . $scheduledTime);
                $absenMasukTime = Carbon::parse($attendance->tanggal . ' ' . $attendance->absen_masuk);

                $latenessMinutes = $scheduledDateTime->diffInMinutes($absenMasukTime, false);
                if ($latenessMinutes > $toleranceMinutes) {
                    return $carry + ($latenessMinutes - $toleranceMinutes);
                }
                return $carry;
            }, 0);

        // Calculate the lateness deduction
        $potonganKeterlambatan = $totalKeterlambatanMenit * $dendaPerMenit;

        // Calculate total absences (days where the user didn't attend)
        $totalTidakHadir = $totalHariKerja - $totalKehadiran;

        // Calculate Potongan Absensi
        $potonganAbsensi = round(($gaji_pokok / $totalHariKerja) * $totalTidakHadir);


        // Prepare data to pass to the view
        $data = [
            'title' => 'Edit Salary',
            'pages' => 'Penggajian',
            'master' => null,
            'salary' => $salary,
            'year' => $year,
            'month' => $month,
            'user' => $user,
            'gaji_pokok' => $gaji_pokok,
            'tunjangan_jabatan' => $tunjangan_jabatan,
            'potonganKeterlambatan' => $potonganKeterlambatan,
            'potonganAbsensi' => $potonganAbsensi,
            'totalHariKerja' => $totalHariKerja,
            'totalKehadiran' => $totalKehadiran,
            'totalKeterlambatan' => $totalKeterlambatan,
            'totalTidakHadir' => $totalTidakHadir,
        ];

        return view('dashboard.penggajian.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'gaji_pokok' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'potongan_absensi' => 'nullable|numeric',
            'potongan_keterlambatan' => 'nullable|numeric',
            'potongan_lainnya' => 'nullable|numeric',
        ]);

        // Find the salary record by ID
        $salary = Salary::findOrFail($id);

        // Calculate the total salary
        $totalGaji = $request->gaji_pokok + $request->tunjangan_jabatan + $request->bonus
            - $request->potongan_absensi - $request->potongan_keterlambatan - $request->potongan_lainnya;

        // Encrypt the total salary using Laravel's built-in encryption
        $encryptedSalary = Crypt::encrypt($totalGaji);

        // Update the salary record
        $salary->update([
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan_jabatan' => $request->tunjangan_jabatan,
            'bonus' => $request->bonus,
            'potongan_absensi' => $request->potongan_absensi,
            'potongan_keterlambatan' => $request->potongan_keterlambatan,
            'potongan_lainnya' => $request->potongan_lainnya,
            'encrypted_salary' => $encryptedSalary, // Update the encrypted salary
        ]);

        // Redirect back with a success message
        return redirect()->route('penggajian.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function generatePdf(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Fetch the necessary data
        $karyawanList = User::where('role', 'karyawan')->get();
        $salaries = Salary::where('year', $year)
            ->where('month', $month)
            ->get()
            ->keyBy('user_id');

        // Prepare data for the view
        $data = [
            'karyawanList' => $karyawanList,
            'salaries' => $salaries,
            'year' => $year,
            'month' => $month,
        ];

        // Load the view and convert it into HTML
        $html = view('dashboard.penggajian.pdf', $data)->render();

        // Create an instance of mPDF and generate the PDF
        $mpdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $mpdf->WriteHTML($html);

        // Output the generated PDF (force download)
        return $mpdf->Output('data_gaji_' . $year . '_' . $month . '.pdf', 'D');
    }

    public function employeePayroll(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Get the year from the request, or default to the current year
        $year = $request->get('year', now()->year);

        // Retrieve the salary for the logged-in employee based on the year
        $salaries = Salary::where('user_id', $user->id)
            ->where('year', $year)
            ->get();

        // Decrypt each salary if available
        foreach ($salaries as $salary) {
            if ($salary->encrypted_salary) {
                try {
                    $salary->decrypted_salary = Crypt::decrypt($salary->encrypted_salary);
                } catch (\Exception $e) {
                    $salary->decrypted_salary = 'Error decrypting';
                }
            }
        }

        // Prepare data for the view
        $data = [
            'title' => 'Employee Salary',
            'pages' => 'Penggajian',
            'master' => null,
            'user' => $user,
            'salaries' => $salaries, // Pass the collection of salaries
            'year' => $year,
        ];

        return view('dashboard.penggajian.employee', $data);
    }
}
