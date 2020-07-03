<?php

namespace App\Services\Admin;

use App\Models\AdminUser;

class AdminUserService
{
	/**
	 * 获取用户
	 * @author: FengLei
	 * @time: 2020/7/3 17:06
	 * @param $id
	 * @return mixed
	 */
	public static function findById($id)
	{
		return AdminUser::findOrFail($id);
	}
}
