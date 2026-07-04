<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';
    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
        'tunjangan'
    ];

    // Relasi one-to-many dengan User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
