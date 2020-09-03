<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PermissionRequest;
use App\Services\Admin\PermissionService;
use Arr;
use helpers;
use Spatie\Permission\Models\Permission;
use App\Result;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * 权限树列表
     * @author: FengLei
     * @time: 2020/8/24 20:17
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tree = PermissionService::getAll();
        if (!empty($tree)) {
            $tree = helpers::generateTree($tree->toArray());
        }
        return Result::success([
            'tree' => $tree
        ]);
    }

    /**
     * 添加权限
     * @author: FengLei
     * @time: 2020/8/24 17:56
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PermissionRequest $request)
    {
        // 创建权限
        $permiss = Permission::create([
            'guard_name' => 'api',
            'name' => $request->name,
            'slug' => $request->slug,
            'type' => $request->type,
            'alias_name' => $request->alias_name,
            'parent_id' => $request->parent_id,
            'icon' => $request->get('icon', 'al-icon-record')
        ]);

        return Result::success($permiss);
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
        // 所有子权限ID
        $subClassIds = Arr::pluck(helpers::subClass(
            PermissionService::getAll()->toArray(), $request->id), 'id'
        );
        array_push($subClassIds, $request->id);
        // 删除权限（包括所有子权限）
        Permission::destroy($subClassIds);
        return Result::success();
    }

    /**
     * 编辑权限
     * @author: FengLei
     * @time: 2020/6/30 18:47
     * @param Request $requestPermissionsControllerPermissionsController
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(PermissionRequest $request)
    {
        $permission = Permission::findById($request->id);
        $permission->update($request->all());
        return Result::success();
    }
}
