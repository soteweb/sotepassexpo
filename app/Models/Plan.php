<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'badge_text',
        'features',
        'has_ruleta',
        'has_tv_draw',
        'is_active',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'has_ruleta' => 'boolean',
            'has_tv_draw' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Stands / empresas que contrataron este plan.
     */
    public function stands(): HasMany
    {
        return $this->hasMany(Stand::class);
    }

    /**
     * Precio formateado en moneda local (Guaraníes).
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price <= 0) {
            return 'Gratis / Demostración';
        }

        return number_format($this->price, 0, ',', '.').' Gs.';
    }
}
