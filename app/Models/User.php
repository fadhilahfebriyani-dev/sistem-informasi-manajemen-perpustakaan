<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin' = Kepala Perpustakaan | 'petugas' = Petugas Perpustakaan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Helper: cek apakah user adalah admin (kepala perpustakaan)
    // -------------------------------------------------------
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // -------------------------------------------------------
    // Helper: cek apakah user adalah petugas perpustakaan
    // -------------------------------------------------------
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }
}