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
        'is_trial' => 'boolean',
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'custom_questions' => 'array',
    ];

    /**
     * Verifica si el evento del stand ya finalizó por fecha.
     */
    public function isExpired(): bool
    {
        if (! $this->event_end_date) {
            return false;
        }

        return now()->startOfDay()->gt($this->event_end_date->endOfDay());
    }

    /**
     * Retorna los días restantes del evento, o null si no tiene fecha límite.
     */
    public function daysRemaining(): ?int
    {
        if (! $this->event_end_date) {
            return null;
        }

        $diff = (int) now()->startOfDay()->diffInDays($this->event_end_date->endOfDay(), false);

        return max(0, $diff);
    }

    /**
     * Formato legible del rango de fechas del evento.
     */
    public function formattedEventDates(): string
    {
        if (! $this->event_start_date && ! $this->event_end_date) {
            return 'Edición Actual';
        }

        if ($this->event_start_date && $this->event_end_date) {
            return $this->event_start_date->format('d/m/Y').' al '.$this->event_end_date->format('d/m/Y');
        }

        return $this->event_end_date ? 'Hasta el '.$this->event_end_date->format('d/m/Y') : 'Desde el '.$this->event_start_date->format('d/m/Y');
    }

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

    /**
     * Opciones de perfil/actividad configuradas para el stand
     *
     * @return array<int, string>
     */
    public function getProfileOptions(): array
    {
        if (! empty($this->custom_questions['profiles']) && is_array($this->custom_questions['profiles'])) {
            return $this->custom_questions['profiles'];
        }

        return [
            'Productor Agrícola',
            'Ganadero',
            'Contratista Rural',
            'Ingeniero Agrónomo',
            'Estudiante / Académico',
            'Comerciante / Proveedor',
            'Visitante General',
        ];
    }

    /**
     * Retorna el modo de tema visual ('light' o 'dark') configurado para el formulario del stand.
     */
    public function getThemeMode(): string
    {
        $mode = $this->custom_questions['theme_mode'] ?? 'light';

        return in_array($mode, ['light', 'dark'], true) ? $mode : 'light';
    }

    /**
     * Indica si el formulario del stand está configurado en modo oscuro.
     */
    public function isDarkMode(): bool
    {
        return $this->getThemeMode() === 'dark';
    }
}
