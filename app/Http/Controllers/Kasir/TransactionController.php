<?php
// app/Http/Controllers/Kasir/TransactionController.php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Transaction;
use App\Models\DetailTransaksi;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'details.course'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('kasir.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $courses = Course::where('status', 'aktif')->get();
        $customers = Customer::orderBy('nama', 'asc')->get();
        
        return view('kasir.transactions.create', compact('courses', 'customers'));
    }

    public function storeCustomer(Request $request)
    {
        try {
            $request->validate([
                'nama_customer' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'no_telp' => 'required|string|max:20',
                'alamat' => 'nullable|string',
                'jenis_kelamin' => 'nullable|in:L,P',
            ]);

            $customer = Customer::create([
                'nama' => $request->nama_customer,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan!',
                'customer_id' => $customer->id_customer,
                'customer_nama' => $customer->nama,
                'customer_email' => $customer->email,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Ambil data dari request JSON
            $data = $request->json()->all();
            if (empty($data)) {
                $data = $request->all();
            }
            
            // Validasi data
            if (!isset($data['paket']) || empty($data['paket'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal 1 paket kursus'
                ]);
            }
            
            if (!isset($data['id_customer']) || empty($data['id_customer'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih customer terlebih dahulu'
                ]);
            }
            
            if (!isset($data['uang_bayar']) || $data['uang_bayar'] <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Masukkan jumlah uang bayar'
                ]);
            }
            
            // Hitung total harga
            $totalHarga = 0;
            $detailItems = [];
            
            foreach ($data['paket'] as $item) {
                $course = Course::find($item['id_paket']);
                if (!$course) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paket kursus tidak ditemukan'
                    ]);
                }
                
                $hargaPaket = $course->harga + ($course->biaya_sertifikat ?? 0);
                $totalHarga += $hargaPaket;
                
                $detailItems[] = [
                    'id_paket' => $course->id_paket,
                    'harga_satuan' => $hargaPaket,
                    'jumlah' => $item['jumlah'] ?? 1,
                    'subtotal' => $hargaPaket * ($item['jumlah'] ?? 1)
                ];
            }
            
            // Validasi uang bayar
            $uangBayar = (int) $data['uang_bayar'];
            if ($uangBayar < $totalHarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uang bayar kurang dari total harga Rp ' . number_format($totalHarga, 0, ',', '.')
                ]);
            }
            
            // Generate kode transaksi
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // AMBIL ID USER YANG BENAR
            $userId = auth()->id();
            
            // Jika auth()->id() masih string, coba dapatkan dari user yang login
            if (!is_numeric($userId)) {
                $user = auth()->user();
                $userId = $user ? $user->id : 1;
            }
            
            \Log::info('User ID yang digunakan: ' . $userId);
            
            // SESUAIKAN DENGAN STRUKTUR TABEL
            $transaction = Transaction::create([
                'kode_transaksi' => $kodeTransaksi,
                'id_customer' => $data['id_customer'],
                'id_user' => $userId,
                'tanggal_transaksi' => now()->format('Y-m-d'),
                'total_harga' => $totalHarga,
                'uang_bayar' => $uangBayar,
                'uang_kembali' => $uangBayar - $totalHarga,
            ]);
            
            // Simpan detail transaksi
            foreach ($detailItems as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'id_paket' => $detail['id_paket'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'jumlah' => $detail['jumlah'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'data' => [
                    'kode_transaksi' => $kodeTransaksi,
                    'total_harga' => $totalHarga,
                    'uang_bayar' => $uangBayar,
                    'uang_kembali' => $uangBayar - $totalHarga,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $transaksi = Transaction::with(['customer', 'details.course'])
                        ->findOrFail($id);
        
        return view('kasir.transactions.show', compact('transaksi'));
    }

    public function print($id)
    {
        $transaksi = Transaction::with(['customer', 'details.course'])
                        ->findOrFail($id);
        
        return view('kasir.transactions.print', compact('transaksi'));
    }
}