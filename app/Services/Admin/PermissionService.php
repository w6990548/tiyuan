<?php

namespace App\Services\Admin;

use App\Models\Permission;

class PermissionService
{
    /**
     * 获取用户拥有的菜单权限ID
     * @author: FengLei
     * @time: 2020/8/20 20:51
     * @param $user
     * @return mixed
     */
    public static function getPermissionIdsByUser($user)
    {
        return $user->getPermissionsViaRoles()->pluck('id')->toArray();
    }

    /**
     * 获取所有权限
     * @author: FengLei
     * @time: 2020/6/30 14:32
     * @param string[] $filed
     * @param array $type
     * @param array $ids
     * @return mixed
     */
    public static function getAll($filed = ['*'], array $type = [], array $ids = [])
    {
        return Permission::select($filed)
            ->when(!empty($type), function ($query) use ($type) {
                $query->whereIn('type', $type);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();
    }
}
