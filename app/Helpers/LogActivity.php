<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    /**
     * Mencatat aktivitas user
     *
     * @param string $aktivitas
     * @param string $tipe
     */
    public static function log($aktivitas, $tipe = 'Aktivitas')
    {
        try {

            $id_user = Auth::check() ? Auth::user()->id : null;

            ActivityLog::create([
                'id_user'        => $id_user, // HARUS ANGKA ID USER
                'aktivitas'      => $aktivitas,
                'tipe_aktivitas' => $tipe,
                'ip_address'     => Request::ip(),
                'waktu'          => now()->format('H:i:s'),
                'created_at'     => now(),
                'updated_at'     => now()
            ]);

        } catch (\Exception $e) {
            // Supaya aplikasi tidak error kalau log gagal
        }
    }

    /**
     * Log saat user login
     */
    public static function login()
    {
        self::log('User login ke sistem', 'Login');
    }

    /**
     * Log saat user logout
     */
    public static function logout()
    {
        self::log('User logout dari sistem', 'Logout');
    }
}