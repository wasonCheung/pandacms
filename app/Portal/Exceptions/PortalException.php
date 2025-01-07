<?php
declare(strict_types=1);

namespace App\Portal\Exceptions;

use RuntimeException;

class PortalException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
