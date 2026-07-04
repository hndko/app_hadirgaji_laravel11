<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AbsensiSetting;
use App\Models\Jabatan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwalAbsensi = AbsensiSetting::first();
        $attendance = Attendance::where('user_id', Auth::id())->where('tanggal', now()->toDateString())->first();

        // Total karyawan
        $totalKaryawan = User::where('role', 'karyawan')->count();

        // Total karyawan yang sudah absen (hanya absen masuk untuk hari ini)
        $totalAbsenMasukToday = Attendance::where('tanggal', now()->toDateString())
            ->whereNotNull('absen_masuk')
            ->count();

        $data = [
            'title' => 'Dashboard',
            'pages' => 'Dashboard',
            'master' => null,
            'jadwalAbsensi' => $jadwalAbsensi,
            'attendance' => $attendance,
            'countKaryawan' => User::where('role', 'karyawan')->get()->count(),
            'countAdmin' => User::where('role', 'admin')->get()->count(),
            'countJabatan' => Jabatan::all()->count(),
            'totalAbsenMasukToday' => $totalAbsenMasukToday,
        ];

        return view('dashboard.index', $data);
    }

    /**
     * Absen Masuk
     */
    public function absenMasuk()
    {
        $user = Auth::user();
        $jadwalAbsensi = AbsensiSetting::first();
        $tanggal = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        // Cek jika sudah absen masuk
        $attendance = Attendance::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
        if ($attendance && $attendance->absen_masuk) {
            return redirect()->back()->with('error', 'Anda sudah absen masuk hari ini.');
        }

        // Catatan untuk absensi
        $catatan = null;
        if ($currentTime > $jadwalAbsensi->jam_masuk) {
            $toleransi = Carbon::parse($jadwalAbsensi->jam_masuk)->addMinutes($jadwalAbsensi->toleransi_keterlambatan);
            if (now()->greaterThan($toleransi)) {
                $catatan = 'Terlambat';
            }
        }

        // Cek apakah hari libur
        $isHoliday = Holiday::where('tanggal', $tanggal)->exists() || now()->isWeekend();
        if ($isHoliday) {
            $catatan = 'Lembur';
        }

        // Simpan data absen masuk
        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'tanggal' => $tanggal],
            ['absen_masuk' => $currentTime, 'catatan' => $catatan]
        );

        return redirect()->back()->with('success', 'Absen masuk berhasil.');
    }

    /**
     * Absen Pulang
     */
    public function absenPulang()
    {
        $user = Auth::user();
        $jadwalAbsensi = AbsensiSetting::first();
        $tanggal = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        // Cek jika sudah absen pulang
        $attendance = Attendance::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
        if (!$attendance || !$attendance->absen_masuk) {
            return redirect()->back()->with('error', 'Anda belum absen masuk.');
        }
        if ($attendance->absen_pulang) {
            return redirect()->back()->with('error', 'Anda sudah absen pulang hari ini.');
        }

        // Cek apakah sudah waktunya absen pulang
        if ($currentTime < $jadwalAbsensi->jam_pulang) {
            return redirect()->back()->with('error', 'Belum waktunya absen pulang.');
        }

        // Simpan data absen pulang
        $attendance->update(['absen_pulang' => $currentTime]);

        return redirect()->back()->with('success', 'Absen pulang berhasil.');
    }

    public function scanQr(Request $request)
    {
        // Assuming the QR code will provide a user identifier or some attendance token
        $user = Auth::user();
        $tanggal = now()->toDateString();

        // Get the QR data (in this case, we assume the NIP is scanned)
        $scannedData = trim($request->input('qr_data')); // Trim to remove extra spaces

        // Parse the URL to extract the NIP
        $parsedUrl = parse_url($scannedData);
        $nip = null;

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['nip'])) {
                $nip = $queryParams['nip']; // Extract the 'nip' parameter from the URL
            }
        }

        // Check if the extracted NIP matches the authenticated user's NIP
        if (!$nip || strcasecmp($nip, $user->nip) !== 0) {
            return redirect()->back()->with('error', 'Invalid QR Code. Scanned data: ' . $scannedData);
        }

        // Check if already scanned today
        $attendance = Attendance::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
        if ($attendance && $attendance->absen_masuk) {
            return redirect()->back()->with('error', 'You have already checked in.');
        }

        // Proceed with the same logic as absenMasuk() to register the attendance
        $jadwalAbsensi = AbsensiSetting::first();
        $currentTime = now()->format('H:i:s');

        $catatan = null;
        if ($currentTime > $jadwalAbsensi->jam_masuk) {
            $toleransi = Carbon::parse($jadwalAbsensi->jam_masuk)->addMinutes($jadwalAbsensi->toleransi_keterlambatan);
            if (now()->greaterThan($toleransi)) {
                $catatan = 'Terlambat';
            }
        }

        // Cek apakah hari libur
        $isHoliday = Holiday::where('tanggal', $tanggal)->exists() || now()->isWeekend();
        if ($isHoliday) {
            $catatan = 'Lembur';
        }

        // Save the attendance
        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'tanggal' => $tanggal],
            ['absen_masuk' => $currentTime, 'catatan' => $catatan]
        );

        return redirect()->back()->with('success', 'QR Code successfully scanned. Attendance recorded.');
    }

    public function scanQrPulang(Request $request)
    {
        $user = Auth::user();
        $tanggal = now()->toDateString();

        // Get the QR data (in this case, we assume the NIP is scanned)
        $scannedData = trim($request->input('qr_data')); // Trim to remove extra spaces

        // Parse the URL to extract the NIP
        $parsedUrl = parse_url($scannedData);
        $nip = null;

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['nip'])) {
                $nip = $queryParams['nip']; // Extract the 'nip' parameter from the URL
            }
        }

        // Check if the extracted NIP matches the authenticated user's NIP
        if (!$nip || strcasecmp($nip, $user->nip) !== 0) {
            return redirect()->back()->with('error', 'Invalid QR Code. Scanned data: ' . $nip);
        }

        // Check if already scanned for "Absen Pulang" today
        $attendance = Attendance::where('user_id', $user->id)->where('tanggal', $tanggal)->first();
        if (!$attendance || !$attendance->absen_masuk) {
            return redirect()->back()->with('error', 'Anda belum absen masuk.');
        }
        if ($attendance->absen_pulang) {
            return redirect()->back()->with('error', 'Anda sudah absen pulang hari ini.');
        }

        // Check if it's time to "Absen Pulang"
        $jadwalAbsensi = AbsensiSetting::first();
        $currentTime = now()->format('H:i:s');

        if ($currentTime < $jadwalAbsensi->jam_pulang) {
            return redirect()->back()->with('error', 'Belum waktunya absen pulang.');
        }

        // Save the "Absen Pulang" attendance
        $attendance->update(['absen_pulang' => $currentTime]);

        return redirect()->back()->with('success', 'QR Code berhasil di-scan. Absen pulang berhasil.');
    }
}
