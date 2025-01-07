<?php

declare(strict_types=1);

namespace App\Admin\Exceptions;

class AdminException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
