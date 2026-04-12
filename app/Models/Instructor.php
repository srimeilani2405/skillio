<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $primaryKey = 'id_instructors';

    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'spesialisasi',
        'status'
    ];

    // ✅ RELASI KE COURSE (paket kursus yang diajar)
    public function courses()
    {
        return $this->hasMany(Course::class, 'id_instructors', 'id_instructors');
    }

    // ✅ RELASI KE CUSTOMER (siswa) melalui tabel transaksi
    public function students()
    {
        // Ambil semua customer yang melakukan transaksi pada course milik instructor ini
        return Customer::whereHas('transactions.detailTransaksis.course', function($query) {
            $query->where('id_instructors', $this->id_instructors);
        })->get();
    }
}