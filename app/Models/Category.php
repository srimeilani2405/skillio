<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'nama_category',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi ke course packages
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'id_kategori', 'kategori_id');
    }
}