<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\AccountOrPasswordException;
use App\Exceptions\BaseResponseException;
use App\Exceptions\DataNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Result;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /**
     * 登陆接口
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author: FengLei
     * @time: 2020/6/17 15:32
     */
    public function login(LoginRequest $request)
    {
        $captchaData = Cache::get($request->captcha_key);
        if (!$captchaData) {
            throw new DataNotFoundException('图片验证码已失效');
        }

        if (app()->environment('production')) {
            // 防止时序攻击，使用 hash_equals 函数来进行比较
            if (!hash_equals(strtolower($captchaData['code']), strtolower($request->captcha))) {
                Cache::forget($request->captcha_key);
                throw new BaseResponseException('验证码错误');
            }
        }

        $credentials['username'] = $request->username;
        $credentials['password'] = $request->password;
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            throw new AccountOrPasswordException();
        }
        return Result::success([
            'token' => $token,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    public function user(Request $request)
    {
        return Result::success($request->user());
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return Result::success('退出成功');
    }
}
