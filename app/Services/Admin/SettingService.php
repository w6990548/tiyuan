<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

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
        $settings = Cache::rememberForever('settings', function () {
            $settings = collect();
            Setting::all()->each(function ($item) use ($settings) {
                if (in_array($item->key, Setting::SWITCH_LIST)) {
                    $item->value = (bool)$item->value;
                }
                $settings->put($item->key, $item->value);
            });
            return $settings;
        });

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
