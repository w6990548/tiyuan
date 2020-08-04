<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    \Debugbar::disable();
        Schema::defaultStringLength(191);
        // 开启数据库操作记录
        DB::enableQueryLog();
        // 非生产环境 记录数据库操作日志
        if(!app()->environment('production')){
            DB::listen(function ($query) {
                Log::channel('query')->debug('sql listen', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time.'ms',
                ]);
            });
        }
    }
}
