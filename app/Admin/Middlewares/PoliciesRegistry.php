<?php

declare(strict_types=1);

namespace App\Admin\Middlewares;

use App\Admin\Policies\RolePolicy;
use App\Foundation\Enums\DefaultRole;
use App\Foundation\Models\Role;
use Illuminate\Support\Facades\Gate;

class PoliciesRegistry
{
    public const POLICIES = [
        Role::class => RolePolicy::class,
    ];

    public function handle($request, $next)
    {
        foreach (self::POLICIES as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole(DefaultRole::SuperAdmin) ? true : null;
        });

        return $next($request);
    }
}
