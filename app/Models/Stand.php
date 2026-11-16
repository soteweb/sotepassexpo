<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stand extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'access_pin',
    ];

    protected $casts = [
        'allow_spins' => 'boolean',
        'is_active' => 'boolean',
        'custom_questions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class)->orderBy('id', 'asc');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class)->orderBy('id', 'desc');
    }

    public function spins(): HasMany
    {
        return $this->hasMany(RuletaSpin::class);
    }

    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class)->orderBy('id', 'desc');
    }
}
