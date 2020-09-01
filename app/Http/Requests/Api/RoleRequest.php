<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|between:2,50',
            'alias_name' => 'required|between:2,15',
            'keys' => 'array',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '角色标识',
            'alias_name' => '角色名称',
            'keys' => '权限组',
        ];
    }
}
