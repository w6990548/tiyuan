<?php

namespace App\Exceptions;

use App\ResultCode;

class UnloginException extends BaseResponseException
{
    public function __construct($message = "您尚未登录")
    {
        parent::__construct($message, ResultCode::UN_LOGIN);
    }
}
