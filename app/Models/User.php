<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Transaction;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // HAPUS cast 'hashed' — kita hash manual pakai Hash::make()
    protected $casts = [];

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_user', 'id');
    }
}