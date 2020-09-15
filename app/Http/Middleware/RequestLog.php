<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class RequestLog
{
    /**
     * Created by FengLei on 2020/9/15 15:08
     * @param $request
     * @param Closure $next
     * @return mixed|JsonResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::guard('api')->user();
        session(['adminUser' => $user]);
        if ($user instanceof Model) {
            $user = $user->toArray();
        }

        // 如果非生产环境, 记录请求日志
        $logData = [
            'request' => [
                'ip' => $request->ip(),
                'fullUrl' => $request->fullUrl(),
                'header' => $request->header(),
                'params' => $request->all(),
                'user' => $user,
            ]
        ];

        $responseData = json_decode($response->getContent(), 1);

        if (!App::environment('production')) {
            $logData['response'] = [
                'statusCode' => $response->getStatusCode(),
                'content' => $responseData ?? $response->getContent(),
            ];
        }
        Log::channel('request')->info('request listen ', $logData);

        // 如果是错误请求, 记录错误日志
        if ($response instanceof JsonResponse) {
            if (!isset($responseData['code'])) {
                $logData['sql_log'] = DB::getQueryLog();
                Log::channel('error-daily')->error('request listen ', $logData);
            }
        }

        return $response;
    }
}
