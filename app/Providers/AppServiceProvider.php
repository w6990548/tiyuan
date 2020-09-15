<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ESClientBuilder;

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
        if (!app()->environment('production')) {
            DB::listen(function ($query) {
                Log::channel('query')->debug('sql listen', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                ]);
            });
        }

        // 注册一个名为 es 的单例
        /*$this->app->singleton('es', function () {
            // 从配置文件读取 Elasticsearch 服务器列表
            $builder = ESClientBuilder::create()->setHosts(config('database.elasticsearch.hosts'));
            // 如果是开发环境
            if (app()->environment() === 'local') {
                // 配置日志，Elasticsearch 的请求和返回数据将打印到日志文件中，方便调试
                $builder->setLogger(app('log')->driver());
            }
            return $builder->build();
        });*/
    }
}
