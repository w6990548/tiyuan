<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Permission;
use App\Result;
use App\Services\Admin\PermissionService;
use App\Services\Admin\SettingService;
use helpers;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * 获取拥有权限的菜单
     * @author: FengLei
     * @time: 2020/8/20 20:58
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeftMenu(Request $request)
    {
        // 获取系统配置项
        $settings = SettingService::getValueBuKey('admin_logo', 'icp_code', 'icp_url', 'site_qr_code');

        $permissionIds = [];
        // 不是超级管理员
        if (!AdminUser::isAdmin($request->user())) {
            // 获取所有从用户角色继承的权限（菜单权限）ID
            $permissionIds = PermissionService::getPermissionIdsByUser($request->user());
            if (empty($permissionIds)) {
                return Result::success([
                    'settings' => $settings,
                    'menus' => $permissionIds,
                    'slugs' => $permissionIds,
                ]);
            }
        }

        $permission = PermissionService::getAll('*', [
            Permission::TYPE_MENU, Permission::TYPE_PAGE
        ], $permissionIds);

        // 所有菜单项
        $menus = $permission->filter(function ($item) {
            return $item->type == Permission::TYPE_MENU;
        });

        // 获取拥有权限的菜单
        $menus = helpers::generateTree($menus->toArray());
        return Result::success([
            'settings' => $settings,
            'menus' => $menus,
            'slugs' => $permission->pluck('name')->toArray(),
        ]);
    }
}
