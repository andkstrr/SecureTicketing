<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - SECURE IMPLEMENTATION
 *
 * Model ini menggunakan best practices:
 * - Password otomatis di-hash via mutator
 * - Hidden attributes untuk keamanan
 * - Proper casting untuk tipe data
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * SECURITY: Hanya field yang boleh diisi via mass assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * SECURITY: Password dan remember_token tidak akan muncul
     * saat model di-convert ke array/JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * SECURITY: 'hashed' memastikan password selalu di-hash
     * saat di-set (Laravel 10+)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Auto-hash password!
        ];
    }

    /**
     * Relationship: User memiliki banyak tickets
     * (Integrasi dengan Minggu 2-3)
     */
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }

    /**
     * Scope untuk mencari user berdasarkan email
     * SECURE: Menggunakan Eloquent (parameterized query)
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
