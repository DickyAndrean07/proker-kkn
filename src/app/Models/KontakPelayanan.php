<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontakPelayanan extends Model
{
    use SoftDeletes;

    protected $table = 'kontak_pelayanans';

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    // ─── Public eligibility scopes ───────────────────────────────────────────

    /**
     * Only records whose parent Dusun is ACTIVE are publicly visible.
     * Uses whereHas to avoid JOIN collisions.
     */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }

    /**
     * Only records with both latitude and longitude present appear on maps.
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
