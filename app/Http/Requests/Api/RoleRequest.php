<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->route()->uri) {
            case 'api/admin/roles/create':
                return [
                    'name' => 'required|between:2,50|unique:roles',
                    'alias_name' => 'required|between:2,15',
                    'keys' => 'array',
                ];
                break;
            case 'api/admin/roles/edit':
                return [
                    'id' => 'required|integer|min:0',
                    'name' => 'required|between:2,50|unique:roles,name,'.$this->get('id'),
                    'alias_name' => 'required|between:2,15',
                    'keys' => 'array',
                ];
                break;
        }
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
