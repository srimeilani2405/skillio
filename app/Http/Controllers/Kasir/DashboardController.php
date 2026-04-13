<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // ← FIX: Auto-sync status kursus berdasarkan kuota terbaru
        Course::with('detailTransaksis')->get()->each(function($course) {
            $course->checkAndUpdateStatus();
        });

        // ← FIX: Ambil SEMUA kursus, bukan hanya yang aktif
        $courses = Course::with(['jenjang', 'mapel', 'instructor'])
                    ->get();

        $jenjangList = Category::all();

        // ← FIX: Hitung total semua kursus, bukan hanya aktif
        $totalKursus          = Course::count();
        $totalTransaksiHari   = Transaction::whereDate('tanggal_transaksi', today())->count();
        $totalPendapatanHari  = Transaction::whereDate('tanggal_transaksi', today())->sum('total_harga');
        $totalPendapatanBulan = Transaction::whereMonth('tanggal_transaksi', now()->month)
                                    ->whereYear('tanggal_transaksi', now()->year)
                                    ->sum('total_harga');
        $totalTransaksi       = Transaction::count();

        return view('kasir.dashboard', compact(
            'courses', 'jenjangList',
            'totalKursus', 'totalTransaksiHari',
            'totalPendapatanHari', 'totalPendapatanBulan', 'totalTransaksi'
        ));
    }
}