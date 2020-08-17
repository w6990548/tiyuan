<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Redis;

class SettingService
{
    /**
     * 获取所有配置项
     * @author: FengLei
     * @time: 2020/7/21 14:16
     * @return mixed
     */
    public static function getAll()
    {
        $settings = Redis::get('settings');
        if (empty($settings)) {
            $settings = Setting::all();
            if ($settings->isNotEmpty()) {
                $settings->each(function ($item) use ($settings) {
                    if (in_array($item->key, Setting::SWITCH_LIST)) {
                        $item->value = (bool)$item->value;
                    }
                    $settings->put($item->key, $item->value);
                });
                Redis::set('settings', json_encode($settings));
            }
        } else {
            $settings = collect(json_decode($settings));
        }

        return $settings;
    }

    /**
     * 获取指定配置项
     * @author: FengLei
     * @time: 2020/7/21 14:24
     * @param mixed ...$keys
     * @return mixed
     */
    public static function getValueBuKey(...$keys)
    {
        $settings = self::getAll();
        if ($keys) {
            $settings = $settings->only($keys);
        }
        return $settings;
    }
}
