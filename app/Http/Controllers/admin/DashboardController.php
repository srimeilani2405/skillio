<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKursus      = Course::count();
        $totalKategori    = Category::count();
        $totalPengajar    = Instructor::count();
        $totalUser        = User::count();
        $totalTransaksi   = Transaction::count();
        $pendapatanTotal  = Transaction::sum('total_harga');

        // Grafik transaksi per bulan (tahun ini)
        $transaksiPerBulan = Transaction::select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $grafikData = array_fill(0, 12, 0);
        foreach ($transaksiPerBulan as $t) {
            $grafikData[$t->bulan - 1] = $t->total;
        }

        // ACTIVITY LOG KHUSUS ADMIN (hanya yang dilakukan oleh user role admin)
        $recentLogs = ActivityLog::whereHas('user', function($query) {
                            $query->where('role', 'admin');
                        })
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->limit(6)
                        ->get();

        return view('admin.dashboard', compact(
            'totalKursus',
            'totalKategori',
            'totalPengajar',
            'totalUser',
            'totalTransaksi',
            'pendapatanTotal',
            'bulanLabel',
            'grafikData',
            'recentLogs'
        ));
    }
}