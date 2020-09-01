<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->route()->uri) {
            case 'api/admin/menus/create':
                return [
                    'name' => 'required|between:2,10',
                    'path' => 'required|between:5,50',
                    'parent_id' => 'required|integer|min:0',
                    'roles' => 'array',
                ];
                break;
            case 'api/admin/menus/edit':
                return [
                    'id' => 'required|integer|min:0',
                    'name' => 'required|between:2,10',
                    'path' => 'required|between:5,50',
                    'parent_id' => 'required|integer|min:0|different:id',
                    'roles' => 'array',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'alias_name' => '菜单名称',
            'parent_id' => '父菜单id',
            'name' => '菜单地址',
            'roles' => '角色组',
        ];
    }
}
