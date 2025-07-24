<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'nip',
        'name',
        'division',
        'agency',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}