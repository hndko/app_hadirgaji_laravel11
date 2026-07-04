<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $data = [
            'title' => 'Authentication Login',
            'pages' => 'Authentication Login',
            'master' => null,
        ];

        return view('auth.index', $data);
    }

    public function login(Request $request)
    {
        // Validate user credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Masukkan format email yang valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if login credentials are valid
        if (Auth::attempt($credentials)) {
            // Regenerate session ID to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', 'Login berhasil.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have successfully logged out.');
    }

    public function profile()
    {
        $user = Auth::user();
        $account = User::with('jabatan')->where('id', $user->id)->first();  // Use first() instead of get()

        $data = [
            'title' => 'Profile Account',
            'pages' => 'Profile Account',
            'master' => null,
            'account' => $account
        ];

        return view('auth.profile', $data);
    }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|unique:users,nip,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi untuk photo
            'password' => 'nullable|min:6', // Validasi optional password
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
            $karyawan->photo = $photoName;
        }

        // Update fields
        $karyawan->nip = $request->nip;
        $karyawan->name = $request->name;
        $karyawan->email = $request->email;

        // Update password jika diberikan
        if (!empty($request->password)) {
            $karyawan->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $karyawan->save();

        return redirect()->route('account')->with('success', 'Profile berhasil diperbarui.');
    }
}
