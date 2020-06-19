<?php

namespace App\Exceptions;

class UnloginException extends BaseResponseException
{
    public function __construct($message = "您尚未登录")
    {
        parent::__construct($message, 10003);
    }
}
