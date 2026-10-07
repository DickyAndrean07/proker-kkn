<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes;

    protected $table = 'pengumumans';

    protected function casts(): array
    {
        return [
            'tanggal_kedaluwarsa' => 'date',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    public function isArchivedFor(CarbonInterface $businessDate): bool
    {
        $timezone = config('app.business_timezone', 'Asia/Jakarta');
        $day = CarbonImmutable::parse(
            CarbonImmutable::instance($businessDate)->setTimezone($timezone)->toDateString(),
            $timezone,
        )->startOfDay();
        $expiresOn = CarbonImmutable::parse(
            $this->tanggal_kedaluwarsa->toDateString(),
            $timezone,
        )->startOfDay();

        return $day->isAfter($expiresOn);
    }

    // ─── Public eligibility scopes ───────────────────────────────────────────

    /**
     * Active announcements: not soft-deleted AND expiry date >= today in Asia/Jakarta.
     * Expiry date = today counts as ACTIVE (boundary per normalization).
     */
    public function scopeActiveAnnouncements($query, ?string $timezone = 'Asia/Jakarta')
    {
        $today = now($timezone)->toDateString();

        return $query->whereDate('tanggal_kedaluwarsa', '>=', $today);
    }

    /**
     * Archived announcements: not soft-deleted AND expiry date < today in Asia/Jakarta.
     */
    public function scopeArchivedAnnouncements($query, ?string $timezone = 'Asia/Jakarta')
    {
        $today = now($timezone)->toDateString();

        return $query->whereDate('tanggal_kedaluwarsa', '<', $today);
    }

    /**
     * Filter to Pengumuman with scope_level = DESA.
     * dusun_id = null; does NOT require an active Dusun parent.
     */
    public function scopeDesaScope($query)
    {
        return $query->where('scope_level', 'DESA');
    }

    /**
     * Filter to Pengumuman with scope_level = DUSUN.
     */
    public function scopeDusunScope($query)
    {
        return $query->where('scope_level', 'DUSUN');
    }

    /**
     * Applied only for DUSUN-scoped Pengumuman.
     * Do NOT call on DESA-scoped queries (dusun_id = null).
     */
    public function scopeWithinActiveDusun($query)
    {
        return $query->whereHas('dusun', fn ($q) => $q->where('status_dusun', 'ACTIVE'));
    }
}
