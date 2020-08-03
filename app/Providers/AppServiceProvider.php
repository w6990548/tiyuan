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
	    DB::listen(function($query) {
		    $tmp = str_replace('?', '"'.'%s'.'"', $query->sql);
		    $tmp = vsprintf($tmp, $query->bindings);
		    $tmp = str_replace("\\","",$tmp);
		    Log::info('执行时间: '.$query->time.'ms; '.$tmp.PHP_EOL);
	    });
    }
}
