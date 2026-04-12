<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'nama',         
        'email',
        'no_telp',
        'alamat',
        'jenis_kelamin',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_customer', 'id_customer');
    }
}