<?php

namespace App\Exceptions;

use App\Result;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseResponseException extends HttpResponseException
{
    public function __construct($message = "未知错误", $code = 500)
    {
        $response = Result::error($code, $message);
        parent::__construct($response);
        $this->message = $message;
        $this->code = $code;
    }
}
