<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Draw extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'draw_date' => 'datetime',
    ];

    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'winner_lead_id');
    }
}
