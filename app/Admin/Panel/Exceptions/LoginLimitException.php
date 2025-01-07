<?php

declare(strict_types=1);

namespace App\Admin\Panel\Exceptions;

use App\Admin\Exceptions\AdminException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class LoginLimitException extends AdminException
{
    public function __construct(
        public TooManyRequestsException $tooManyRequestsException,
    ) {

        $message = __class(__CLASS__)
            ->replacements([
                'seconds' => $this->tooManyRequestsException->secondsUntilAvailable,
            ]);

        parent::__construct((string) $message);
    }
}
