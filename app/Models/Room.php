<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',       // nama ruangan
        'capacity',   // kapasitas
        'location',   // lokasi (optional, kalau ada)
        'description' // deskripsi (optional)
    ];

    // Relasi ke Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
