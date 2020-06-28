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
        $adminUsers = AdminUser::paginate($request->pageSize);
        return Result::success([
            'list' => $adminUsers->items(),
            'total' => $adminUsers->total(),
        ]);
    }
}
