<?php

namespace App\Http\Controllers;

use App\Models\PenaltySetting;
use Illuminate\Http\Request;

class PenaltySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penalties = PenaltySetting::all();
        $data = [
            'title' => 'Setting Denda Keterlambatan',
            'pages' => 'Denda Keterlambatan',
            'master' => null,
            'penalties' => $penalties
        ];

        return view('dashboard.penalties.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Denda Keterlambatan',
            'pages' => 'Denda Keterlambatan',
            'master' => null,
        ];

        return view('dashboard.penalties.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_denda' => 'required|integer|min:0',
        ]);

        PenaltySetting::create($request->all());

        return redirect()->route('penalties.index')->with('success', 'Denda keterlambatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penalty = PenaltySetting::findOrFail($id);
        $data = [
            'title' => 'Edit Denda Keterlambatan',
            'pages' => 'Denda Keterlambatan',
            'master' => null,
            'penalty' => $penalty
        ];

        return view('dashboard.penalties.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jumlah_denda' => 'required|integer|min:0',
        ]);

        $penalty = PenaltySetting::findOrFail($id);
        $penalty->update($request->all());

        return redirect()->route('penalties.index')->with('success', 'Denda keterlambatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penalty = PenaltySetting::findOrFail($id);
        $penalty->delete();

        return redirect()->route('penalties.index')->with('success', 'Denda keterlambatan berhasil dihapus.');
    }
}
