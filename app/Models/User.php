<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User
 * @package App\Models
 *
 * Model untuk tabel 'users'.
 * Bertanggung jawab untuk otentikasi pengguna dan relasinya dengan tabel lain.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',
    ];

    /**
     * Atribut yang disembunyikan dari array/JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: user membuat banyak booking.
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi: user sebagai PIC (admin yang menyetujui booking).
     *
     * @return HasMany
     */
    public function picBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pic_user_id');
    }

    /**
     * Relasi: user milik satu divisi.
     *
     * @return BelongsTo
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}