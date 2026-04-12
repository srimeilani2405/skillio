<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'kode_transaksi',
        'id_customer',
        'id_user',
        'tanggal_transaksi',
        'total_harga',
        'uang_bayar',
        'uang_kembali',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // ✅ RELASI KE DETAIL TRANSAKSI (YANG BENAR)
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // ✅ ALIAS untuk detailTransaksis (jika ada yang memanggil)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}