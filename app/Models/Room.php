<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Room
 * @package App\Models
 *
 * Model untuk tabel 'rooms'.
 * Mengelola data ruangan dan relasinya dengan divisi dan booking.
 */
class Room extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'capacity',
        'location',
        'description',
        'is_available',
        'division_id',
    ];

    /**
     * Relasi: satu ruangan dimiliki satu divisi.
     *
     * @return BelongsTo
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relasi: satu ruangan bisa punya banyak booking.
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}