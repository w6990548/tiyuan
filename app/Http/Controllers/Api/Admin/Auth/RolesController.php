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
        $roleData = Role::paginate($request->pageSize);
        return Result::success([
            'list' => $roleData->items(),
            'total' => $roleData->total(),
        ]);
    }
}
