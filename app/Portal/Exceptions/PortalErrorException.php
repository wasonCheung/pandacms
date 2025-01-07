<?php

declare(strict_types=1);

namespace App\Portal\Exceptions;

use RuntimeException;
use Throwable;

class PortalErrorException extends RuntimeException
{
    public function __construct(string $message, Throwable $ex)
    {
        parent::__construct($message, 0, $ex);
    }
}
