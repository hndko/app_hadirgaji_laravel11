<?php

namespace App\Models;

use App\Models\Jabatan;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'jabatan_id',
        'photo',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi one-to-many inverse ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
