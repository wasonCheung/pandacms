<?php

declare(strict_types=1);

namespace App\Foundation\Models;

use App\Admin\Services\PanelService;
use App\Foundation\Services\UserService;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
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

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === PanelService::ID) {
            return app(UserService::class)->hasAdminModuleRole($this);
        }

        return true;
    }
}
