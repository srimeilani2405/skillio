<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course_packages';
    protected $primaryKey = 'id_paket';

    protected $fillable = [
        'nama',
        'id_mapel',
        'mata_pelajaran', 
        'id_kategori',
        'id_instructors',
        'harga',
        'biaya_sertifikat',
        'deskripsi',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'masa_aktif_hari',
        'durasi_per_sesi',
        'kuota_peserta',
        'status',
    ];

    protected $casts = [
        'hari' => 'array',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // --- RELASI ---

    public function jenjang()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'kategori_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'id_instructors', 'id_instructors');
    }

    public function detailTransaksis()
    {
        // Pastikan nama model DetailTransaksi dan foreign key-nya benar
        return $this->hasMany(DetailTransaksi::class, 'id_paket', 'id_paket');
    }

    // --- ACCESSOR & LOGIKA KUOTA (PENTING UNTUK DASHBOARD) ---

    public function getTerdaftarAttribute()
    {
        // Menghitung jumlah peserta yang sudah beli paket ini
        return $this->detailTransaksis()->sum('jumlah');
    }

    public function sisaKuota()
    {
        // Fungsi yang dipanggil di Dashboard Kasir
        return $this->kuota_peserta - $this->terdaftar;
    }

    public function isKuotaTersedia()
    {
        return $this->sisaKuota() > 0;
    }

    // --- FORMATTING ---

    public function getJamMulaiFormattedAttribute() {
        return $this->jam_mulai ? $this->jam_mulai->format('H:i') : '-';
    }

    public function getJamSelesaiFormattedAttribute() {
        return $this->jam_selesai ? $this->jam_selesai->format('H:i') : '-';
    }
}