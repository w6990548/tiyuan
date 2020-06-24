<?php

namespace App\Http\Requests\Api;

use App\Exceptions\NoPermissionException;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 不是超级管理员 且 没有权限
        if (!$this->user()->hasRole('admin') && !$this->user()->can($this->path())) {
            throw new NoPermissionException('权限不足 '.$this->path());
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purview_name' => 'required|between:3,10',
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
