<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purview_name' => 'required|between:2,10',
            'pid' => 'required|integer|min:0',
            'name' => 'required|between:5,50',
        ];
    }

    public function attributes()
    {
        return [
            'purview_name' => '权限名称',
            'pid' => '父权限',
            'name' => '权限地址',
        ];
    }
}
