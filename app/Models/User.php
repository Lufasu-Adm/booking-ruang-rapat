<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking;
use App\Models\Division;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Relasi ke bookings yang dibuat user ini
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ✅ Relasi ke booking sebagai PIC
    public function picBookings()
    {
        return $this->hasMany(Booking::class, 'pic_user_id');
    }

    // ✅ Relasi ke divisi
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}