<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Jabatan;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = User::with('jabatan')->get();
        $data = [
            'title' => 'Karyawan',
            'pages' => 'Karyawan',
            'master' => null,
            'karyawans' => $karyawans
        ];

        return view('dashboard.karyawan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        $data = [
            'title' => 'Tambah Karyawan',
            'pages' => 'Karyawan',
            'master' => null,
            'jabatans' => $jabatans
        ];

        return view('dashboard.karyawan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'jabatan_id' => 'required|exists:jabatans,id',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi untuk photo
        ]);

        // Handle photo upload jika ada
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Simpan photo ke folder public/photos secara langsung
            $photo->move(public_path('photos'), $photoName);
        }

        // Simpan data karyawan baru
        User::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'email' => $request->email,
            'jabatan_id' => $request->jabatan_id,
            'role' => 'karyawan',
            'password' => Hash::make($request->password),
            'photo' => $photoName, // Simpan nama file photo jika ada
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $karyawan = User::findOrFail($id);
        $jabatans = Jabatan::all();
        $data = [
            'title' => 'Edit Karyawan',
            'pages' => 'Karyawan',
            'master' => null,
            'karyawan' => $karyawan,
            'jabatans' => $jabatans
        ];

        return view('dashboard.karyawan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|unique:users,nip,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'jabatan_id' => 'required|exists:jabatans,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi untuk photo
        ]);

        // Temukan karyawan yang akan di-update
        $karyawan = User::findOrFail($id);

        // Handle photo upload jika ada
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Simpan photo baru ke folder public/photos
            $photo->move(public_path('photos'), $photoName);

            // Hapus photo lama jika ada
            if ($karyawan->photo && file_exists(public_path('photos/' . $karyawan->photo))) {
                unlink(public_path('photos/' . $karyawan->photo));
            }

            // Update karyawan dengan photo baru
            $karyawan->update([
                'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'jabatan_id' => $request->jabatan_id,
                'photo' => $photoName, // Update dengan photo baru
            ]);
        } else {
            // Update karyawan tanpa mengubah photo
            $karyawan->update([
                'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'jabatan_id' => $request->jabatan_id,
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = User::findOrFail($id);

        // Hapus photo jika ada
        if ($karyawan->photo && Storage::exists('public/photos/' . $karyawan->photo)) {
            Storage::delete('public/photos/' . $karyawan->photo);
        }

        // Hapus data karyawan
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    public function generateQrCode(Request $request, $id)
    {
        // Retrieve the employee
        $karyawan = User::findOrFail($id);

        // Define the QR code content, such as the employee NIP
        $qrContent = route('absen.scan', ['nip' => $karyawan->nip]);

        // Create the QR Code instance
        $qrCode = new QrCode($qrContent);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        // Use PNG writer to generate the image
        $writer = new PngWriter();
        $qrImage = $writer->write($qrCode);

        // Define the filename for the QR code
        $qrFileName = 'qr_' . $karyawan->nip . '.png';
        $qrFilePath = public_path('photos/' . $qrFileName);

        // Save the QR code image directly to the public/photos folder
        file_put_contents($qrFilePath, $qrImage->getString());

        // If the request is an AJAX call, return a JSON response with the QR image path
        if ($request->ajax()) {
            return response()->json(['qrImagePath' => asset('photos/' . $qrFileName)]);
        }

        // Pass the employee and QR code path to the view
        $data = [
            'title' => 'Dashboard',
            'pages' => 'Dashboard',
            'master' => null,
            'karyawan' => $karyawan,
            'qrImagePath' => 'photos/' . $qrFileName
        ];

        return view('dashboard.karyawan.qr', $data);
    }

    /**
     * Download the generated QR Code
     */
    public function downloadQrCode($id)
    {
        $karyawan = User::findOrFail($id);
        $qrImagePath = public_path('photos/qr_' . $karyawan->nip . '.png');

        // Check if the QR code file exists in the public/photos directory
        if (!file_exists($qrImagePath)) {
            return redirect()->back()->withErrors('QR code not found.');
        }

        // Return the QR code for download
        return response()->download($qrImagePath, 'QR_Karyawan_' . $karyawan->nip . '.png');
    }
}
