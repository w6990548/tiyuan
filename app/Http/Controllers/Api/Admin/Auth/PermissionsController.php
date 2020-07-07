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

class PermissionsController extends Controller
{
    /**
     * 权限树列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tree = PermissionService::getAll();
        if ($tree->isNotEmpty()) {
            $tree = PermissionService::converPermissionsToTree($tree);
        }

        return Result::success($tree);
    }

    /**
     * 获取左侧导航菜单项
     * @author: FengLei
     * @time: 2020/7/7 18:38
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function leftmenu(Request $request)
    {
        $tree = PermissionService::getAll();
        if ($tree->isNotEmpty()) {
            $tree = PermissionService::converLeftMenuToTree($tree, $request->user());
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
        if ($request->pid > 0) {
            $parent = Permission::findById($request->pid);
            if ($parent['level'] > 2) {
                throw new BaseResponseException('权限最多增加到3级');
            }
        }

        DB::beginTransaction();
        try {
            // 创建权限
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'api',
                'purview_name' => $request->purview_name,
                'level' => $request->pid === 0 ? 1 : $parent['level'] + 1,
                'pid' => $request->pid,
            ]);

            // 获取用户拥有的角色
            $roleName = $request->user()->getRoleNames()->first();
            $roles = Role::findByName($roleName);
            // 通过角色添加权限
            $permission->assignRole($roles);
            DB::commit();
        } catch (\Exception $e) {
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
        // 三级权限 直接删除，二级权限-需要删除三级，顶级权限-需要删除二三级权限

        if (!is_null($permission->childPermission)) {
            if ($permission->level === 1) {
                foreach ($permission->childPermission as $secondItem) {
                    if (!is_null($secondItem->childPermission)) {
                        // 删除三级权限
                        $secondItem->childPermission()->delete();
                    }
                }
            }
            // 删除二级权限
            $permission->childPermission()->delete();
        }

        // 删除权限
        $permission->delete();

        return Result::success();
    }

    /**
     * 编辑权限
     * @author: FengLei
     * @time: 2020/6/30 18:47
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $permission = Permission::findById($request->id);
        $permission->update($request->except('id', 'pid'));
        return Result::success();
    }
}
