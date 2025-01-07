<?php

declare(strict_types=1);

namespace App\Admin\Contracts;

use Filament\Notifications\Notification;

interface PanelNotification
{
    public function getNotification(): Notification;
}
