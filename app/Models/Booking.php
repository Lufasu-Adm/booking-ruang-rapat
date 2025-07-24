<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'date',
        'start_time',
        'end_time',
        'division_id',
        'department',
        'purpose', 
        'status',
        'pic_user_id'
    ];

    // Relasi ke user peminjam
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke ruangan
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke PIC (admin/user yang bertanggung jawab)
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    // Relasi ke divisi peminjam
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}