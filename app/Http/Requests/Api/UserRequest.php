<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
	public function rules()
	{
		return [
			'username' => 'required|between:3,10|unique:admin_users,username',
			'password' => 'required|between:6,18|alpha_dash',
		];
	}

	public function attributes()
	{
		return [
			'username' => '用户名',
			'password' => '密码',
		];
	}
}
