<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Division
 * @package App\Models
 *
 * Model untuk tabel 'divisions'.
 * Mengelola data divisi dan relasinya dengan user, ruangan, dan booking.
 */
class Division extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Relasi: satu divisi punya banyak user.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: satu divisi punya banyak ruangan.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Relasi: satu divisi punya banyak booking.
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}