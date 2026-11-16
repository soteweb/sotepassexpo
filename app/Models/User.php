<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'role',
        'phone',
        'company_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verifica si el usuario es administrador general de Soteweb.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'master']);
    }

    /**
     * Verifica si el usuario es expositor / cliente de stand.
     */
    public function isExpositor(): bool
    {
        return $this->role === 'expositor';
    }

    /**
     * Stands registrados bajo este usuario.
     */
    public function stands(): HasMany
    {
        return $this->hasMany(Stand::class);
    }

    /**
     * Stand principal del usuario expositor.
     */
    public function stand(): HasOne
    {
        return $this->hasOne(Stand::class)->latestOfMany();
    }
}
