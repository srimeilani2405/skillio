<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $primaryKey = 'id_activity';

    protected $fillable = [
        'id_user',
        'aktivitas',
        'tipe_aktivitas',
        'ip_address',
        'waktu'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }
}
