<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return redirect('/admin');
    // return view('welcome');
    // dd(123);
    return view('admin');
});
// Route::get('/', function () {
//     // return view('/admin/login', 'admin');
//     return redirect('/admin');
// });

// 后端页面
Route::view('/admin', 'admin');

Route::get('/{any}', 'SpaController@index')->where('any', '.*');