<?php

namespace App\Exceptions;

class NoPermissionException extends BaseResponseException
{
    public function __construct($message = "权限不足")
    {
        parent::__construct($message, 10025);
    }
}
