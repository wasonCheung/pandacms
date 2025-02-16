<?php
declare(strict_types=1);

namespace App\Foundation\Models;

use App\Foundation\Enums\DefaultGuard;
use Illuminate\Database\Eloquent\Builder;

class Permission extends \Spatie\Permission\Models\Permission
{
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('guard_name', DefaultGuard::Admin);
    }

    public function scopePortal(Builder $query): Builder
    {
        return $query->where('guard_name', DefaultGuard::Portal);
    }
}
