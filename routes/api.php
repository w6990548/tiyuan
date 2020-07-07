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
    ->group(function () {
        // 登录
        Route::get('login', 'LoginController@login');
        // 图片验证码
        Route::post('captchas', 'CaptchasController@getCaptchas');
        // 登录后可以访问的接口
        Route::middleware('auth:api', 'admin')->group(function () {
            // 获取用户信息
            Route::get('user', 'LoginController@user');
            // 退出登录
            Route::post('logout', 'LoginController@logout');

            /****************************** 用户管理 ******************************/
            // 获取后台用户列表
            Route::get('users', 'Auth\UsersController@getUsers');
            // 添加用户
            Route::post('users/create', 'Auth\UsersController@create');
            // 修改用户
            Route::post('users/edit', 'Auth\UsersController@edit');
            // 删除用户
            Route::post('users/delete', 'Auth\UsersController@delete');
            // 重置密码
            Route::post('users/reset', 'Auth\UsersController@reset');

            /****************************** 角色管理 ******************************/
            // 获取后台用户角色列表
            Route::get('roles', 'Auth\RolesController@getRoles');
            // 添加角色
            Route::post('roles/create', 'Auth\RolesController@create');
            // 编辑角色
            Route::post('roles/edit', 'Auth\RolesController@edit');
            // 删除角色
            Route::post('roles/delete', 'Auth\RolesController@delete');

            /****************************** 权限管理 ******************************/
            // 获取后台权限列表
            Route::get('permissions', 'Auth\PermissionsController@index');
            // 添加权限
            Route::post('permissions/create', 'Auth\PermissionsController@create');
            // 删除权限
            Route::post('permissions/delete', 'Auth\PermissionsController@delete');
            // 编辑权限
            Route::post('permissions/edit', 'Auth\PermissionsController@edit');
            // 左侧导航菜单
            Route::get('leftmenu', 'Auth\PermissionsController@leftmenu');
        });
    });
