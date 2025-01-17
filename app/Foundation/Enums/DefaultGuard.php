<?php

declare(strict_types=1);

namespace App\Foundation\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DefaultGuard: string implements HasColor, HasLabel
{
    case Admin = 'admin';
    case Portal = 'portal';

    public function getLabel(): ?string
    {
        return (string) __enum($this);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Portal => 'success',
        };
    }
}
