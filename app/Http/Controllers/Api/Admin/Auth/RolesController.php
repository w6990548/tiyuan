<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RoleRequest;
use App\Models\Role;
use App\Result;
use App\Services\Admin\PermissionService;
use helpers;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * 角色列表
     * @author: FengLei
     * @time: 2020/7/1 15:11
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles(Request $request)
    {
        $roleData = Role::with('permissions:id,name,parent_id')
            ->paginate($request->pageSize);

        foreach ($roleData as $item) {
            $item->checkedKeys = [];
            if ($item['permissions']->isNotEmpty()) {
                $item->checkedKeys = $item['permissions']->toArray();
            }
        }

        $permissionsData = PermissionService::getAll();
        if (!empty($permissionsData)) {
            $permissionsData = helpers::generateTree($permissionsData->toArray());
        }
        return Result::success([
            'roles' => $roleData->items(),
            'permissions' => $permissionsData,
            'total' => $roleData->total(),
        ]);
    }

    /**
     * 添加角色
     * @author: FengLei
     * @time: 2020/7/1 19:03
     * @param RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RoleRequest $request)
    {
        // 创建角色
        $role = Role::create([
            'guard_name' => 'api',
            'name' => $request->name,
            'alias_name' => $request->alias_name
        ]);

        if (!empty($request->keys)) {
            $permissionData = PermissionService::getAll('*', [], $request->keys)->pluck('id');
            $role->syncPermissions($permissionData);
        }
        // 添加权限
        return Result::success($role);
    }

    /**
     * 编辑角色
     * @author: FengLei
     * @time: 2020/7/2 18:39
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(RoleRequest $request)
    {
        $role = Role::findById($request->id);
        if ($role->name == Role::ADMIN) {
            throw new NoPermissionException('超级管理员角色禁止修改');
        }
        $permissionData = [];
        if (!empty($request->keys)) {
            $permissionData = PermissionService::getAll('*', [], $request->keys);
        }
        // 更新权限
        $role->syncPermissions($permissionData);
        $role->update($request->all());
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
        if ($role->name == Role::ADMIN) {
            throw new NoPermissionException('超级管理员角色禁止删除');
        }
        Role::destroy($request->id);
        return Result::success();
    }
}
