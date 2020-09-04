<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        abort(401, '侧沙士大夫金卡');
    }
}
