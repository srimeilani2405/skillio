<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\MataPelajaran;
use App\Models\Transaction;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::with(['jenjang', 'mapel', 'instructor'])
                    ->where('status', 'aktif')
                    ->get();

        $jenjangList = Category::all();

        $totalKursus = Course::where('status', 'aktif')->count();
        $totalTransaksiHari = Transaction::whereDate('tanggal_transaksi', today())->count();
        $totalPendapatanHari = Transaction::whereDate('tanggal_transaksi', today())->sum('total_harga');
        $totalPendapatanBulan = Transaction::whereMonth('tanggal_transaksi', now()->month)
                                    ->whereYear('tanggal_transaksi', now()->year)
                                    ->sum('total_harga');
        $totalTransaksi = Transaction::count();

        return view('kasir.dashboard', compact(
            'courses', 'jenjangList',
            'totalKursus', 'totalTransaksiHari',
            'totalPendapatanHari', 'totalPendapatanBulan', 'totalTransaksi'
        ));
    }
}