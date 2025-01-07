<?php

declare(strict_types=1);

namespace App\Admin\Panel\Exceptions;

use App\Admin\Exceptions\AdminException;

class LoginInvalidException extends AdminException
{
    public function __construct()
    {
        parent::__construct((string) __class(__CLASS__));
    }
}
