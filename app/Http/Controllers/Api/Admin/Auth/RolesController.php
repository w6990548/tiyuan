<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Result;
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
	    $roleData = Role::with('permissions:name,level,purview_name')
		    ->paginate($request->pageSize);
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
	    $permissionData = Permission::whereIn('id', $request->keys)->get();
	    // 创建角色
	    $role = Role::create(['name' => $request->name]);
	    // 添加权限
	    $role->syncPermissions($permissionData);
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
