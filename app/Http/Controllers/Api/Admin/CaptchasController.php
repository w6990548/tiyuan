<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Result;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    /**
     * 获取图片验证码以及key
     * @param CaptchaBuilder $captchaBuilder
     * @return \Illuminate\Http\JsonResponse
     * @author: FengLei
     * @time: 2020/6/15 15:17
     */
    public function getCaptchas(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(10);

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinute(5);
        Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);

        return Result::success([
            'captcha_key' => $key,
            'expired_time' => $expiredAt->toDateTimeString(),
            'captcha_image' => $captcha->inline()
        ]);
    }
}
