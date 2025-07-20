<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: user membuat banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi: user sebagai PIC (admin yang menyetujui booking)
     */
    public function picBookings()
    {
        return $this->hasMany(Booking::class, 'pic_user_id');
    }

    /**
     * Relasi: user milik satu divisi
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}