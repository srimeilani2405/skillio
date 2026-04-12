<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user']);

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->customer . '%');
            });
        }

        $transactions   = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $totalPendapatan = $transactions->sum('total_harga');

        return view('owner.transactions.index', compact('transactions', 'totalPendapatan'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['customer', 'user', 'details.course'])->findOrFail($id);
        return view('owner.transactions.show', compact('transaction'));
    }

    public function exportPdf(Request $request)
    {
        $dari_tanggal    = $request->dari_tanggal    ?? now()->startOfMonth()->toDateString();
        $sampai_tanggal  = $request->sampai_tanggal  ?? now()->toDateString();

        $query = Transaction::with(['customer', 'user']);

        $query->whereDate('tanggal_transaksi', '>=', $dari_tanggal);
        $query->whereDate('tanggal_transaksi', '<=', $sampai_tanggal);

        if ($request->filled('customer')) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->customer . '%');
            });
        }

        $transactions    = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $totalPendapatan = $transactions->sum('total_harga');

        $pdf = Pdf::loadView('owner.transactions.export-pdf', compact(
            'transactions',
            'totalPendapatan',
            'dari_tanggal',
            'sampai_tanggal'
        ))->setPaper('a4', 'landscape');

        $filename = 'laporan-transaksi-' . $dari_tanggal . '-sd-' . $sampai_tanggal . '.pdf';

        return $pdf->download($filename);
    }
}