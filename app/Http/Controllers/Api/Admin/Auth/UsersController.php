<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Models\AdminUser;
use App\Models\Role;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @var AdminUser
     */
    private $adminUser;

    public function __construct(AdminUser $adminUser)
    {
        $this->adminUser = $adminUser;
    }

    /**
     * 用户列表
     * @author: FengLei
     * @time: 2020/7/3 14:42
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $adminUsers = $this->adminUser::with('roles:id,name,alias_name')
            ->paginate($request->pageSize);

        // 角色列表（除超级管理员）
        $roles = Role::where('id', '>', Role::ADMIN_ID)->get(['id', 'name', 'alias_name']);
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
        $adminUser = $this->adminUser::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // 添加角色
        $adminUser->assignRole($request->role);
        return Result::success($adminUser);
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
        $adminUser = $this->adminUser->findById($request->id);
        $adminUser->update($request->all());
        // 站长不得更换
        if (!AdminUser::isAdmin($adminUser)) {
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
        $adminUser = $this->adminUser->findById($request->id);
        if (AdminUser::isAdmin($adminUser)) {
            throw new NoPermissionException('超级管理员禁止删除');
        }
        $adminUser->delete();
        return Result::success();
    }

    /**
     * 重置密码
     * @author: FengLei
     * @time: 2020/7/6 17:18
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
            'password' => 'required|between:6,18|alpha_dash'
        ]);
        $adminUser = $this->adminUser->findById($request->id);

        // 超级管理员只能自己重置密码，别人有权限也不行
        if (!AdminUser::isAdmin($request->user()) && $request->id == 1) {
            throw new NoPermissionException();
        }
        $adminUser->update([
            'password' => Hash::make($request->password),
        ]);
        return Result::success();
    }
}
