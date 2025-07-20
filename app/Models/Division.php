<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi: satu divisi punya banyak user
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi: satu divisi punya banyak ruangan
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Relasi: satu divisi punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}