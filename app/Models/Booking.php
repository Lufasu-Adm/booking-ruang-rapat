<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Booking
 * @package App\Models
 *
 * Model untuk tabel 'bookings'.
 * Mengelola data pemesanan ruangan dan relasinya dengan user, ruangan, dan divisi.
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
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

    /**
     * Relasi: booking milik satu user peminjam.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: booking untuk satu ruangan.
     *
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi: booking memiliki satu PIC (admin/user yang bertanggung jawab).
     *
     * @return BelongsTo
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    /**
     * Relasi: booking milik satu divisi peminjam.
     *
     * @return BelongsTo
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relasi: satu booking memiliki banyak data kehadiran (attendance).
     *
     * @return HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}