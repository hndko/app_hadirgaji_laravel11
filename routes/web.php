<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiSettingController;
use App\Http\Controllers\PenaltySettingController;

/**
 * Route Authentication
 */
Route::get('/', [AuthController::class, 'showLoginForm'])->name('/');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->name('logout'); // Route to handle logout


/**
 * Route Dashboard Admin & Karyawan
 */

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('absen-masuk', [DashboardController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('absen-pulang', [DashboardController::class, 'absenPulang'])->name('absen.pulang');
    Route::post('absen/scan', [DashboardController::class, 'scanQr'])->name('absen.scan');
    Route::post('absen/scan/pulang', [DashboardController::class, 'scanQrPulang'])->name('absen.scan.pulang');

    Route::resource('data-jabatan', JabatanController::class);

    Route::resource('karyawan', KaryawanController::class);
    Route::get('karyawan/{id}/qr-code', [KaryawanController::class, 'generateQrCode'])->name('karyawan.generateQrCode');
    Route::get('karyawan/{id}/download-qr', [KaryawanController::class, 'downloadQrCode'])->name('karyawan.download-qr');

    Route::resource('absensi-settings', AbsensiSettingController::class);
    Route::get('data-absensi', [AbsensiController::class, 'index'])->name('absensi.index');

    Route::resource('holidays', HolidayController::class);
    Route::resource('penalties', PenaltySettingController::class);


    Route::get('penggajian', [SalaryController::class, 'index'])->name('penggajian.index');
    Route::get('penggajian/create', [SalaryController::class, 'create'])->name('penggajian.create');
    Route::post('penggajian', [SalaryController::class, 'store'])->name('penggajian.store');
    Route::get('penggajian/edit/{id}', [SalaryController::class, 'edit'])->name('penggajian.edit');
    Route::put('penggajian/{id}', [SalaryController::class, 'update'])->name('penggajian.update');
    Route::get('penggajian/pdf', [SalaryController::class, 'generatePdf'])->name('penggajian.pdf');

    Route::get('penggajian/employee', [SalaryController::class, 'employeePayroll'])->name('penggajian.employee');

    Route::get('account', [AuthController::class, 'profile'])->name('account');
    Route::put('account/{id}', [AuthController::class, 'update'])->name('account.update');
});
