<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Result;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;

class RolesController extends Controller
{
	/**
	 * 角色列表及角色拥有的权限
	 * @author: FengLei
	 * @time: 2020/7/1 15:11
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getRoles(Request $request)
	{
		$roleData = Role::with('permissions:id,name,purview_name,pid,level')
			->paginate($request->pageSize);

		foreach ($roleData as $item) {
			if ($item['permissions']->isNotEmpty()) {
				list($level1Data, $level2Data, $level3Data) = PermissionService::converPermissionsToTree($item['permissions'], true);
				$level1Data = PermissionService::specialConverPermissions($level1Data, $level2Data);
				$level2Data = PermissionService::specialConverPermissions($level2Data, $level3Data);
			}
			$item->checkedKeys = array_merge($level1Data, $level2Data, $level3Data);
		}
		return Result::success([
			'list' => $roleData->items(),
			'total' => $roleData->total(),
		]);
	}

	/**
	 * 添加角色
	 * @author: FengLei
	 * @time: 2020/7/1 19:03
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function create(Request $request)
	{
		// 创建角色
		$role = Role::create(['name' => $request->name]);
		$permissionData = PermissionService::getAll($request->keys);
		// 添加权限
		$role->syncPermissions($permissionData);
		return Result::success();
	}

	/**
	 * 编辑角色
	 * @author: FengLei
	 * @time: 2020/7/2 18:39
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit(Request $request)
	{
		$role = Role::findById($request->id);
		$permissionData = PermissionService::getAll($request->keys);
		// 更新权限
		$role->syncPermissions($permissionData);
		$role->update($request->except(['id', 'keys']));
		return Result::success();
	}

	/**
	 * 删除角色
	 * @author: FengLei
	 * @time: 2020/7/2 10:26
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete(Request $request)
	{
		$role = Role::findById($request->id);
		$role->delete();
		return Result::success();
	}
}
