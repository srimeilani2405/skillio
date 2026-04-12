<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKursus = Course::count();
        $totalKategori = Category::count();
        $totalPengajar = Instructor::count();
        $totalTransaksi = Transaction::count();
        $pendapatanTotal = Transaction::sum('total_harga');
        
        // Grafik pendapatan 6 bulan terakhir
        $pendapatanBulanan = Transaction::select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('YEAR(tanggal_transaksi) as tahun'),
                DB::raw('SUM(total_harga) as total')
            )
            ->where('tanggal_transaksi', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        
        // Kursus terlaris
        $kursusTerlaris = DB::table('detail_transaksis')
            ->select('course_packages.nama', DB::raw('SUM(detail_transaksis.jumlah) as total_terjual'))
            ->join('course_packages', 'detail_transaksis.id_paket', '=', 'course_packages.id_paket')
            ->groupBy('course_packages.id_paket', 'course_packages.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();
        
        return view('owner.dashboard', compact(
            'totalKursus',
            'totalKategori',
            'totalPengajar',
            'totalTransaksi',
            'pendapatanTotal',
            'pendapatanBulanan',
            'kursusTerlaris'
        ));
    }
}