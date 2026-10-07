<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminAccount extends Authenticatable
{
    public const ROLE_ADMIN_DUSUN = 'ADMIN_DUSUN';

    public const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';

    protected $table = 'admin_accounts';

    protected $guarded = ['*'];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'removed_at' => 'datetime',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function isAdminDusun(): bool
    {
        return $this->role === self::ROLE_ADMIN_DUSUN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isRemoved(): bool
    {
        return $this->removed_at !== null;
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }
}
