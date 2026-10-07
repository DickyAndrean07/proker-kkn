<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $table = 'dusuns';

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function adminAccounts(): HasMany
    {
        return $this->hasMany(AdminAccount::class, 'dusun_id');
    }

    public function kontakPelayanans(): HasMany
    {
        return $this->hasMany(KontakPelayanan::class, 'dusun_id');
    }

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class, 'dusun_id');
    }

    public function fasilitas(): HasMany
    {
        return $this->hasMany(Fasilitas::class, 'dusun_id');
    }

    public function agendaKegiatans(): HasMany
    {
        return $this->hasMany(AgendaKegiatan::class, 'dusun_id');
    }

    public function pengumumans(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'dusun_id');
    }

    // ─── Public eligibility scopes ───────────────────────────────────────────

    /**
     * Only Dusun with status ACTIVE are visible on the public site.
     */
    public function scopePublicActive($query)
    {
        return $query->where('status_dusun', 'ACTIVE');
    }
}
