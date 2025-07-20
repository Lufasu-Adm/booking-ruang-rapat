<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'location',
        'description',
        'is_available',
        'division_id',
    ];

    /**
     * Relasi: satu ruangan dimiliki satu divisi
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relasi: satu ruangan bisa punya banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}