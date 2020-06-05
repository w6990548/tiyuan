<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function index()
    {
        $list = [
            'code' => 10,
            'number' => 200,
            'message' => '错误123123'
        ];
        return $list;
        dd(123);
        return view('admin');
    }
}