<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe_aktivitas', $request->tipe);
        }

        $logs = $query->paginate(50);
        $users = User::all();

        $totalAktivitas   = ActivityLog::count();
        $aktivitasHariIni = ActivityLog::whereDate('created_at', today())->count();
        $aktivitasMingguIni = ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Statistik per tipe
        $statLogin  = ActivityLog::where('tipe_aktivitas', 'Login')->count();
        $statLogout = ActivityLog::where('tipe_aktivitas', 'Logout')->count();
        $statTambah = ActivityLog::where('tipe_aktivitas', 'Tambah')->count();
        $statEdit   = ActivityLog::where('tipe_aktivitas', 'Edit')->count();
        $statHapus  = ActivityLog::where('tipe_aktivitas', 'Hapus')->count();

        // Range tanggal log
        $firstLog = ActivityLog::oldest()->first();
        $lastLog  = ActivityLog::latest()->first();

        return view('owner.activity_logs.index', compact(
            'logs', 'users',
            'totalAktivitas', 'aktivitasHariIni', 'aktivitasMingguIni',
            'statLogin', 'statLogout', 'statTambah', 'statEdit', 'statHapus',
            'firstLog', 'lastLog'
        ));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('owner.activity_logs.show', compact('log'));
    }
}