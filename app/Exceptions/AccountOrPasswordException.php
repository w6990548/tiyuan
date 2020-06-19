<?php

namespace App\Exceptions;

class AccountOrPasswordException extends BaseResponseException
{
    public function __construct($message = "帐号或密码错误")
    {
        parent::__construct($message, 10005);
    }
}
