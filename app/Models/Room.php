<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\Division;

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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}