<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Umkm extends Model
{
    use SoftDeletes;

    protected $table = 'umkms';

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

    public function produkUmkms(): HasMany
    {
        return $this->hasMany(ProdukUmkm::class, 'umkm_id');
    }

    // ─── Public eligibility scopes ───────────────────────────────────────────

    /**
     * Only UMKM whose parent Dusun is ACTIVE are publicly visible.
     */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }

    /**
     * Only UMKM with both coordinates appear on the map as markers.
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }
}
