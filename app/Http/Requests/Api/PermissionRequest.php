<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->route()->uri) {
            case 'api/admin/permissions/create':
                return [
                    'alias_name' => 'required|between:2,10',
                    'parent_id' => 'required|integer|min:0',
                    'name' => 'required|between:5,50',
                    'slug' => 'required_if:type,2,3|nullable|between:5,50',
                    'type' => 'required|in:1,2,3',
                    'icon' => 'nullable|between:2,50',
                ];
                break;
            case 'api/admin/permissions/edit':
                return [
                    'id' => 'required|integer|min:0',
                    'alias_name' => 'required|between:2,50',
                    'parent_id' => 'required|integer|min:0|different:id',
                    'name' => 'required|between:5,50',
                    'slug' => 'required_if:type,2,3|nullable|between:5,50',
                    'type' => 'required|in:1,2,3',
                    'icon' => 'nullable|between:2,50',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'slug' => '权限标识',
            'alias_name' => '权限名称',
            'parent_id' => '父权限id',
            'name' => '权限地址',
            'type' => '权限类型',
            'icon' => '图标',
        ];
    }
}
