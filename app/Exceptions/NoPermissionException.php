<?php

namespace App\Exceptions;

use App\ResultCode;

class NoPermissionException extends BaseResponseException
{
    public function __construct($message = "权限不足")
    {
        parent::__construct($message, ResultCode::NO_PERMISSION);
    }
}
