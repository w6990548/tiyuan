<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        // 有ID则为编辑
        switch ($this->get('id')) {
            case 0:
                return [
                    'username' => 'required|between:3,10|unique:admin_users,username',
                    'password' => 'required|between:6,18|alpha_dash',
                ];
                break;
            default:
                return [
                    'username' => 'between:3,10|unique:admin_users,username,'.$this->get('id').',id',
                ];
        }
    }

    public function attributes()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }
}
