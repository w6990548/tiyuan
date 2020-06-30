<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\BaseResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PermissionRequest;
use App\Models\Permission as newPermission;
use App\Services\Admin\PermissionService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Result;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsController extends Controller
{
    /**
     * 权限树列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	if ($request->pid === 0) {
		    $tree = PermissionService::getTopPermissionAll();
	    } else {
	        $permissionData = PermissionService::getAll();
	        $tree = PermissionService::converPermissionsToTree($permissionData);
	    }

	    return Result::success($tree);
    }

    /**
     * 添加权限
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PermissionRequest $request)
    {
    	DB::beginTransaction();
	    try {
		    if ($request->pid !== 0) {
			    $parent = Permission::findOrFail($request->pid);
			    if ($parent->level > 2) {
				    throw new BaseResponseException('权限最多增加到3级');
			    }
		    }

	    	// 创建权限
		    Permission::create([
			    'name' => $request->get('name'),
			    'purview_name' => $request->get('purview_name'),
			    'level' => $request->pid === 0 ? 1 : $parent->level + 1,
			    'pid' => $request->pid,
		    ]);

		    // 获取用户拥有的角色
		    $roleName = $request->user()->getRoleNames()->first();
		    $roles = Role::findByName($roleName);
		    // 通过角色添加权限
		    $roles->givePermissionTo($request->get('name'));

		    DB::commit();
	    } catch (\Exception $e){
		    DB::rollBack();
		    throw $e;
	    }

        return Result::success();
    }

	/**
	 * 删除权限
	 * @author: FengLei
	 * @time: 2020/6/29 17:58
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function delete(Request $request)
    {
	    $permission = newPermission::findById($request->id);
		// 所有角色
	    $roles = Role::get();
	    foreach ($roles as $role) {
		    // 移除角色的顶级权限
		    $role->revokePermissionTo($permission);
		    self::dealwith($permission, $role);
	    }

		// 删除权限
	    $permission->delete();

        // 需清除缓存，否则会报错
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return Result::success();
    }

	/**
	 * 处理二三级权限
	 * @author: FengLei
	 * @time: 2020/6/29 17:57
	 * @param $permission [父权限]
	 * @param $role [角色]
	 */
    public static function dealwith($permission, $role)
    {
	    // 下级权限不为空
	    if ($permission->childPermission->isNotEmpty()) {
		    foreach ($permission->childPermission as $secondItem) {
			    // 移除角色的权限
			    $role->revokePermissionTo($secondItem);
			    // 删除权限
			    $secondItem->delete();
			    // 第三级权限
			    self::dealwith($secondItem, $role);
		    }
	    }
    }

    public function edit(Request $request)
    {
    	$permission = PermissionService::getPermissionById($request->id);
	    $permission->update($request->except('id', 'pid'));
	    return Result::success();
    }
}
