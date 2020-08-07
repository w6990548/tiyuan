<?php

namespace App\Http\Controllers\Api\Admin\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleLabelRequest;
use App\Models\Article;
use App\Models\ArticleLabel;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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

    /**
     * 添加标签
     * @author: FengLei
     * @time: 2020/8/3 15:11
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(ArticleLabelRequest $request)
    {
        $articleLabel = ArticleLabel::create($request->all());
        return Result::success($articleLabel);
    }

    /**
     * 删除标签
     * @author: FengLei
     * @time: 2020/8/3 12:26
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $articleLabel = ArticleLabel::findOrFail($request->id);

        // 使用该标签的文章
        $articles = $articleLabel->articles->toArray();

        // 删除文章使用的该标签记录
        $articleLabel->articles()->detach();
        // 删除标签
        $articleLabel->delete();

        $articleIds = [];
        if ($articles) {
            foreach ($articles as $article) {
                $articleIds[] = $article['id'];
            }
        }

        // 涉及到的文章数据重新同步到 redis
        $articles = Article::with('labels')
            ->whereIn('id', $articleIds)
            ->get();
        if ($articles) {
            foreach ($articles as $article) {
                // 同步数据到 redis 中
                Redis::zAdd('articles', $article->id, json_encode($article));
            }
        }

        return Result::success();
    }
}
