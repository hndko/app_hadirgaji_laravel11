<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        $data = [
            'title' => 'Jabatan',
            'pages' => 'Jabatan',
            'master' => null,
            'jabatans' => $jabatans
        ];

        return view('dashboard.jabatan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Jabatan',
            'pages' => 'Jabatan',
            'master' => null,
        ];

        return view('dashboard.jabatan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric',
            'tunjangan' => 'required|numeric',
        ]);

        // Simpan data jabatan baru
        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
        ]);

        return redirect()->route('data-jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $data = [
            'title' => 'Edit Jabatan',
            'pages' => 'Jabatan',
            'master' => null,
            'jabatan' => $jabatan
        ];

        return view('dashboard.jabatan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric',
            'tunjangan' => 'required|numeric',
        ]);

        // Temukan jabatan yang akan di-update
        $jabatan = Jabatan::findOrFail($id);

        // Update data jabatan
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
        ]);

        return redirect()->route('data-jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        // Hapus data jabatan
        $jabatan->delete();

        return redirect()->route('data-jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
