<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSetting;
use Illuminate\Http\Request;

class AbsensiSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = AbsensiSetting::all();
        $data = [
            'title' => 'Setting Absensi',
            'pages' => 'Absensi',
            'master' => null,
            'settings' => $settings
        ];

        return view('dashboard.absensi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Setting Absensi',
            'pages' => 'Absensi',
            'master' => null,
        ];

        return view('dashboard.absensi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required|integer|min:0',
        ]);

        AbsensiSetting::create($request->all());

        return redirect()->route('absensi-settings.index')->with('success', 'Setting absensi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $setting = AbsensiSetting::findOrFail($id);
        $data = [
            'title' => 'Edit Setting Absensi',
            'pages' => 'Absensi',
            'master' => null,
            'setting' => $setting
        ];

        return view('dashboard.absensi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required|integer|min:0',
        ]);

        $setting = AbsensiSetting::findOrFail($id);
        $setting->update($request->all());

        return redirect()->route('absensi-settings.index')->with('success', 'Setting absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = AbsensiSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('absensi-settings.index')->with('success', 'Setting absensi berhasil dihapus.');
    }
}
