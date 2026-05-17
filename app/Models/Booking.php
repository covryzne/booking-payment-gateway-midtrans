<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
    'user_id',
    'nama',
    'no_hp',
    'alamat',
    'tanggal',
    'jam_mulai',
    'jam_selesai',
    'keperluan',
    'durasi_jam',
    'total_harga',
    'status',
    'kode_booking',
    'va_number',
    'qr_code',
    'is_used'
];
}
