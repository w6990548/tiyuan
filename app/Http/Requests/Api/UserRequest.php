<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->route()->uri) {
            case 'api/admin/users/create':
                return [
                    'username' => 'required|between:3,10|unique:admin_users,username',
                    'password' => 'required|between:6,18|alpha_dash',
                ];
                break;
            case 'api/admin/users/edit':
                return [
                    'id' => 'required|integer|min:0',
                    'username' => 'required|between:3,10|unique:admin_users,username,'.$this->get('id'),
                ];
                break;
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
