<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiSetting extends Model
{
    use HasFactory;

    protected $table = 'absensi_settings';
    protected $fillable = [
        'jam_masuk',
        'jam_pulang',
        'toleransi_keterlambatan'
    ];
}
