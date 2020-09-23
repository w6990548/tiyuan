<?php

namespace App\Exceptions;

use App\ResultCode;

class AccountOrPasswordException extends BaseResponseException
{
    public function __construct($message = "帐号或密码错误")
    {
        parent::__construct($message, ResultCode::ACCOUNT_PASSWORD_ERROR);
    }
}
