<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Models\AdminUser;
use App\Models\Role;
use App\Result;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * 用户列表
     * @author: FengLei
     * @time: 2020/7/3 14:42
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $adminUsers = AdminUser::with('roles:id,name')
            ->paginate($request->pageSize);

        // 角色列表（除超级管理员）
        $roles = Role::where('id', '>', 1)->get(['id', 'name']);
        return Result::success([
            'list' => $adminUsers->items(),
            'roles' => $roles,
            'total' => $adminUsers->total(),
        ]);
    }

    /**
     * 添加用户
     * @author: FengLei
     * @time: 2020/7/3 15:43
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserRequest $request)
    {
        $adminUser = AdminUser::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // 添加角色
        $adminUser->assignRole($request->role);
        return Result::success();
    }

    /**
     * 编辑用户
     * @author: FengLei
     * @time: 2020/7/3 17:01
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(UserRequest $request)
    {
        $adminUser = AdminUserService::findById($request->id);
        $adminUser->update($request->except(['id', 'role', 'name']));
        // 站长不得更换
        if (!$adminUser->hasRole(AdminUser::ADMIN)) {
            // 把该用户所有角色删除，并替换为给定数组的角色
            $adminUser->syncRoles($request->role);
        }
        return Result::success();
    }

    /**
     * 删除用户
     * @author: FengLei
     * @time: 2020/7/3 18:53
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $adminUser = AdminUserService::findById($request->id);
        // 站长禁止删除
        if (!$adminUser->hasRole(AdminUser::ADMIN)) {
            $adminUser->delete();
        }
        return Result::success();
    }
}
