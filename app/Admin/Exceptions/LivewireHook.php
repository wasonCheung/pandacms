<?php

declare(strict_types=1);

namespace App\Admin\Exceptions;

use Filament\Notifications\Notification;
use Livewire\ComponentHook;

class LivewireHook extends ComponentHook
{
    public function exception($e, $stopPropagation): void
    {
        if (($e instanceof AdminException || $e instanceof AdminErrorException) === false) {
            return;
        }

        $notification = Notification::make()
            ->body($e->getMessage());

        if ($e instanceof AdminException) {
            $notification->warning();
        } else {
            $notification->danger();
        }

        $stopPropagation();
    }
}
