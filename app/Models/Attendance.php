<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Attendance
 * @package App\Models
 *
 * Model untuk tabel 'attendances'.
 * Mengelola data daftar hadir untuk setiap booking.
 */
class Attendance extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'nip',
        'name',
        'division',
        'agency',
    ];

    /**
     * Relasi: data kehadiran milik satu booking.
     *
     * @return BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}