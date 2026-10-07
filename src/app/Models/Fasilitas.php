<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
    use SoftDeletes;

    protected $table = 'fasilitas';

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

    public function kategoriFasilitas(): BelongsTo
    {
        return $this->belongsTo(KategoriFasilitas::class, 'kategori_fasilitas_id');
    }

    // ─── Public eligibility scopes ───────────────────────────────────────────

    /**
     * Only Fasilitas whose parent Dusun is ACTIVE are publicly visible.
     */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }

    /**
     * Only Fasilitas with both coordinates appear on the map as markers.
     * (Fasilitas requires coordinates per spec, but this scope is explicit for queries.)
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
