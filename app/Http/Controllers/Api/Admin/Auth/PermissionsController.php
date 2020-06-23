<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Result;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function getPermissions(Request $request)
    {
        // 判断用户是否有操作权限
        if (!$request->user()->can($request->path())) {
            throw new NoPermissionException();
        }

        $pid = $request->get('pid', 0);
        $roleData = Permission::where('pid', $pid)
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
}
