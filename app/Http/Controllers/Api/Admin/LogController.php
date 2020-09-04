<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Result;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * 日志列表
     * @author: FengLei
     * @time: 2020/9/4 14:47
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $logs = AdminLog::with('adminUser:id,username')
            ->orderByDesc('id')
            ->paginate($request->input('pageSize'));
        return Result::success([
            'list' => $logs->items(),
            'total' => $logs->total()
        ]);
    }

    /**
     * 删除日志
     * @author: FengLei
     * @time: 2020/9/4 14:52
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        AdminLog::destroy($request->id);
        return Result::success();
    }
}
