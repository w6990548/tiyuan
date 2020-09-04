<?php

namespace App\Http\Middleware\Admin;

use App\Models\AdminLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminLogOperation
{
    /**
     * 操作日志
     * @author: FengLei
     * @time: 2020/9/4 11:54
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        $data = [
            'user_id' => $user ? $user->id : 0,
            'path' => $request->getPathInfo(),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'input' => $this->formatInput($request->input()),
        ];

        try {
            AdminLog::create($data);
        } catch (\Exception $exception) {

        }

        return $next($request);
    }

    protected function formatInput(array $input)
    {
        $fields = ['password'];
        foreach ((array)$fields as $field) {
            if ($field && !empty($input[$field])) {
                $input[$field] = Str::limit($input[$field], 3, '******');
            }
        }

        return json_encode($input);
    }
}
