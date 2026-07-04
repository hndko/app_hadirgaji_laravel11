<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $table = 'salaries';

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'gaji_pokok',
        'tunjangan_jabatan',
        'bonus',
        'potongan_absensi',
        'potongan_keterlambatan',
        'potongan_lainnya',
        'encrypted_salary',
    ];
}
