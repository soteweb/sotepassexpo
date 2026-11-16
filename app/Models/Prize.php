<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prize extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_real_prize' => 'boolean',
        'total_stock' => 'integer',
        'remaining_stock' => 'integer',
        'probability_weight' => 'integer',
    ];

    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class);
    }

    public function spins(): HasMany
    {
        return $this->hasMany(RuletaSpin::class);
    }
}
