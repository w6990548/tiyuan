<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends BaseResponseException
{
    public function __construct($message = "数据不存在")
    {
        parent::__construct($message, 10010);
    }
}
