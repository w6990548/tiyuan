<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\BaseResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PermissionRequest;
use App\Models\Permission as newPermission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Result;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsController extends Controller
{
    /**
     * 权限列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pid = $request->get('pid', 0);
        $roleData = newPermission::where('pid', $pid)
            ->when($pid === 0, function ($query) {
                $query->withCount('childPermission as childCount');
            })->paginate($request->pageSize);

        if ($pid === 0) {
            foreach ($roleData as $item) {
                if ($item->childCount > 0) {
                    $item->hasChildren = true;
                }
            }
        }

        return Result::success([
            'list' => $roleData->items(),
            'total' => $roleData->total(),
        ]);
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

    public function delete(Request $request)
    {
        $permission = Permission::findById($request->get('id'));
        $roles = Role::get();
        foreach ($roles as $role) {
            // 移除角色的权限
            $role->revokePermissionTo($permission);
        }

        $permission->delete();
        // 需清除缓存，否则会报错
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return Result::success();
    }
}
