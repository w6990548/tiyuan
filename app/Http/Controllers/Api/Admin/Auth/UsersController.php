<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Result;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function getUsers(Request $request)
    {
        // 不是超级管理员 且 没有权限
        if (!$request->user()->can($request->path())) {
            throw new NoPermissionException('权限不足 '.$request->path());
        }
        $adminUsers = AdminUser::paginate($request->pageSize);
        return Result::success([
            'list' => $adminUsers->items(),
            'total' => $adminUsers->total(),
        ]);
    }
}
