<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::all();
        $data = [
            'title' => 'Setting Hari Libur',
            'pages' => 'Hari Libur',
            'master' => null,
            'holidays' => $holidays
        ];

        return view('dashboard.holidays.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Hari Libur',
            'pages' => 'Hari Libur',
            'master' => null,
        ];

        return view('dashboard.holidays.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        $data = [
            'title' => 'Edit Hari Libur',
            'pages' => 'Hari Libur',
            'master' => null,
            'holiday' => $holiday
        ];

        return view('dashboard.holidays.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil dihapus.');
    }
}
