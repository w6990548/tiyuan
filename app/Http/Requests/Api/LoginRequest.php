<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|alpha_dash|min:6',
            'captcha_key' => 'required|string',
            'captcha' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '图片验证码 key',
            'captcha' => '图片验证码',
        ];
    }
}
