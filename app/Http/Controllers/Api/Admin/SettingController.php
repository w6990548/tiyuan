<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * 获取所有配置项
     * @author: FengLei
     * @time: 2020/7/20 17:36
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $settings = Cache::rememberForever('settings', function () {
            $settings = Setting::all()->pluck('value', 'key');
            foreach ($settings as $key => $value) {
                if (in_array($key, Setting::SWITCH_LIST)) {
                    $settings[$key] = (bool)$settings[$key];
                }
            }
            return $settings;
        });
        return Result::success($settings);
    }

    /**
     * 保存配置项
     * @author: FengLei
     * @time: 2020/7/20 16:06
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        DB::transaction(function () use ($request) {
            $settings = Setting::all()->keyBy('key');

            foreach ($request->all() as $key => $value) {
                if (isset($settings[$key])) {
                    if ($settings[$key]->value <> $value) {
                        $settings[$key]->update(['value' => $value]);
                    }
                } else {
                    Setting::create(['key' => $key, 'value' => $value]);
                }
            }

            Cache::forget('settings');
            Cache::forget('settings:'.$key);
        });

        return Result::success();
    }
}
