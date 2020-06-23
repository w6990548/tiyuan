<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('admin')
    ->namespace('Api\Admin')
    ->group(function() {
        // 登录
        Route::get('login', 'LoginController@login')->name('login');
        // 图片验证码
        Route::post('captchas', 'CaptchasController@getCaptchas')->name('captchas.getCaptchas');
        // 登录后可以访问的接口
        Route::middleware('auth:api')->group(function () {
            // 获取用户信息
            Route::get('user', 'LoginController@user');
            // 退出登录
            Route::post('logout', 'LoginController@logout');

            /**
             * 权限模块
             */
            // 获取后台用户列表
            Route::get('users', 'Auth\UsersController@getUsers');
            // 获取后台用户角色列表
            Route::get('roles', 'Auth\RolesController@getRoles');
            // 获取后台权限列表
            Route::get('permissions', 'Auth\PermissionsController@getPermissions');
        });
});
