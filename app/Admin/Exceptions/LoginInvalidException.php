<?php

declare(strict_types=1);

namespace App\Admin\Exceptions;

use Filament\Notifications\Notification;
use App\Admin\Contracts\PanelNotification;
use App\Admin\Exceptions\AdminException;

class LoginInvalidException extends AdminException implements PanelNotification
{
    public function __construct()
    {
        parent::__construct(__('admin.exceptions.login_invalid'));
    }

    public function getNotification(): Notification
    {
        return Notification::make()
            ->body($this->getMessage())
            ->danger();
    }
}
