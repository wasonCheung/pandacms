<?php

declare(strict_types=1);

namespace App\Admin\Exceptions;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Notifications\Notification;
use App\Admin\Contracts\PanelNotification;
use App\Admin\Exceptions\AdminException;

class LoginLimitException extends AdminException implements PanelNotification
{
    public function __construct(
        public TooManyRequestsException $tooManyRequestsException,
    ) {
        parent::__construct(__('admin/exceptions.login_limit.notification.title',
            [
                'seconds' => $this->tooManyRequestsException->secondsUntilAvailable,
            ]));
    }

    public function getNotification(): Notification
    {
        return Notification::make()
            ->title($this->getMessage())
            ->body(__('admin/exceptions.login_limit.notification.body', [
                'seconds' => $this->tooManyRequestsException->secondsUntilAvailable,
            ]))
            ->danger();
    }
}
