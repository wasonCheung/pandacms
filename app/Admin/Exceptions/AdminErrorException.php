<?php

declare(strict_types=1);

namespace App\Admin\Exceptions;

use RuntimeException;
use Throwable;

class AdminErrorException extends RuntimeException
{
    public function __construct(string $message, Throwable $ex)
    {
        parent::__construct($message, 0, $ex);
    }
}
