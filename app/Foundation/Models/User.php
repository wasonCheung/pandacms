<?php

declare(strict_types=1);

namespace App\Foundation\Models;

use App\Admin\Services\PanelService;
use App\Foundation\Enums\DefaultRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    public static function getAdminUsers(): Collection
    {
        return self::role(DefaultRole::Admin)->get();
    }

    public static function getSuperAdminUsers(): Collection
    {
        return self::role(DefaultRole::SuperAdmin)->get();
    }

    public static function getMemberUsers(): Collection
    {
        return self::role(DefaultRole::Member)->get();
    }

    public function hasAdminGuardRole(): bool
    {
        return $this->roles()->admin()->first() !== null;
    }

    public function hasPortalGuardRole(): bool
    {
        return $this->roles()->portal()->first() !== null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === PanelService::ID) {
            return $this->hasAdminGuardRole();
        }

        return true;
    }
}
