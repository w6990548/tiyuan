<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Result;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getRoles(Request $request)
    {
        // 不是超级管理员 且 没有权限
        if (!$request->user()->can($request->path())) {
            throw new NoPermissionException('权限不足 '.$request->path());
        }
        $roleData = Role::paginate($request->pageSize);
        return Result::success([
            'list' => $roleData->items(),
            'total' => $roleData->total(),
        ]);
    }
}
