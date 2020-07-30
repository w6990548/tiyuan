<?php

namespace App\Http\Controllers\Api\Admin\Article;

use App\Http\Controllers\Controller;
use App\Models\ArticleLabel;
use App\Result;
use Illuminate\Http\Request;

class ArticleLabelController extends Controller
{
    /**
     * 标签列表
     * @author: FengLei
     * @time: 2020/7/30 16:55
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $articleLabels = ArticleLabel::all();
        return Result::success($articleLabels);
    }
}
