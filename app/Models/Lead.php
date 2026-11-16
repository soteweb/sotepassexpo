<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'custom_answers' => 'array',
    ];

    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class);
    }

    public function spin(): HasOne
    {
        return $this->hasOne(RuletaSpin::class);
    }

    public function spins(): HasMany
    {
        return $this->hasMany(RuletaSpin::class);
    }
}
