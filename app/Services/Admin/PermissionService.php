<?php

namespace App\Services\Admin;

use App\Models\Permission as newPermission;

class PermissionService
{
	/**
	 * 获取所有权限
	 * @author: FengLei
	 * @time: 2020/6/30 14:32
	 * @param array $ids [权限IDs]
	 * @return mixed
	 */
	public static function getAll($ids = [])
	{
		return newPermission::when($ids, function ($query) use ($ids) {
			$query->whereIn('id', $ids);
		})->get();
	}

	/**
	 * 获取所有顶级权限
	 * @author: FengLei
	 * @time: 2020/6/30 14:33
	 * @return mixed
	 */
	public static function getTopPermissionAll()
	{
		return newPermission::where('pid', 0)->get();
	}

	/**
	 * 将权限数组转换为树形结构
	 * @author: FengLei
	 * @time: 2020/6/30 10:52
	 * @param $permissionData [所有权限]
	 * @param bool $waith
	 * @return array|array[]
	 */
	public static function converPermissionsToTree($permissionData, $withArray = false)
	{
		foreach ($permissionData as $k => $permission) {
			$permissions[$k] = $permission->toArray();
		}

		$tree = [];
		$level1 = [];
		$level2 = [];
		$level3 = [];
		foreach ($permissions as $item) {
			switch ($item['level']) {
				case 1:
					$level1[$item['id']] = (object)$item;
					break;
				case 2:
					$level2[$item['id']] = (object)$item;
					break;
				case 3:
					$level3[$item['id']] = (object)$item;
					break;
				default:
					break;
			}
		}

		if ($withArray) {
			return [$level1, $level2, $level3];
		}

		$level2 = self::compositeStructure($level3, $level2);
		$level1 = self::compositeStructure($level2, $level1);

		foreach ($level1 as $l) {
			$tree[] = $l;
		}

		return $tree;
	}

	/**
	 * 组装子权限到父权限
	 * @author: FengLei
	 * @time: 2020/6/30 11:13
	 * @param $permission [子权限]
	 * @param $supPermission [父权限]
	 * @return mixed
	 */
	private static function compositeStructure($permission, $supPermission)
	{
		foreach ($permission as $item) {
			if ($item->pid == $supPermission[$item->pid]->id) {
				$supPermission[$item->pid]->children[] = $item;
			}
		}
		return $supPermission;
	}

	/**
	 * 特殊处理编辑页的权限列表
	 * @author: FengLei
	 * @time: 2020/7/3 12:17
	 * @param $permissions [父权限]
	 * @param $subPermissions [子权限]
	 * @return mixed
	 */
	public static function specialConverPermissions($permissions, $subPermissions)
	{
		foreach ($permissions as $pid => $permission) {
			foreach ($subPermissions as $subPermission) {
				if ($pid == $subPermission->pid) {
					unset($permissions[$pid]);
				}
			}
		}
		return $permissions;
	}
}
