<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'matapelajarans';

    protected $fillable = [
        'nama_mapel'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'id_mapel', 'id');
    }
}