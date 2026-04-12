<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_transaksi',
        'id_paket',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id_transaksi');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_paket', 'id_paket');
    }
}