<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status'   => 1,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Catat aktivitas login
            LogActivity::log('User ' . $user->name . ' berhasil login ke sistem', 'Login');

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'kasir') {
                return redirect('/kasir/dashboard');
            } elseif ($user->role === 'owner') {
                return redirect('/owner/dashboard');
            }

            Auth::logout();
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function logout(Request $request)
    {
        // Catat aktivitas logout sebelum logout
        if (Auth::check()) {
            LogActivity::log('User ' . Auth::user()->name . ' logout dari sistem', 'Logout');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}