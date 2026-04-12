<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('owner.profile', compact('user'));
    }

    // ==========================================
    // ROMBAK TOTAL BAGIAN UPDATE PROFIL (NAMA & USERNAME)
    // ==========================================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ], [
            'name.required' => 'Nama lengkap wajib diisi!',
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username ini sudah dipakai orang lain, silakan ganti!',
        ]);

        // 2. Update data via Model
        $user->name = $request->name;
        $user->username = $request->username;
        $user->save();

        // 3. Catat log
        LogActivity::log('Owner mengupdate data profil', 'Profil');

        // =========================================================
        // INI KUNCI UTAMANYA BANG: RE-LOGIN USER DENGAN DATA BARU
        // =========================================================
        Auth::login($user);

        // 4. Redirect kembali
        return redirect()->route('owner.profile')->with('success', 'Data profil berhasil diperbarui!');
    }

    // ==========================================
    // BAGIAN PASSWORD ABAIKAN SAJA SEPERTI PERMINTAANMU
    // ==========================================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('owner.profile')
                ->with('error', 'User tidak ditemukan!');
        }

        $freshUser = DB::table('users')->where('id', $user->id)->first();

        if (!Hash::check($request->current_password, $freshUser->password)) {
            return redirect()->route('owner.profile')
                ->with('error', 'Password lama yang Anda masukkan salah!')
                ->withInput();
        }

        $newPassword = Hash::make($request->password);

        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => $newPassword]);

        $check = DB::table('users')->where('id', $user->id)->first();
        if (!Hash::check($request->password, $check->password)) {
            return redirect()->route('owner.profile')
                ->with('error', 'Gagal menyimpan password baru. Coba lagi!');
        }

        LogActivity::log('Owner mengganti password', 'Profil');

        return redirect()->route('owner.profile')
            ->with('success', 'Password berhasil diganti!');
    }
}
