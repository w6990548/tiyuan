<?php

namespace App\Http\Controllers\Api\Admin\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleRequest;
use App\Models\Article;
use App\Result;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 文章列表
     * @author: FengLei
     * @time: 2020/7/28 16:53
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $articles = Article::with('labels')
            ->orderByDesc('created_at')
            ->paginate($request->pageSize);
        return Result::success([
            'list' => $articles->items(),
            'total' => $articles->total(),
        ]);
    }

    /**
     * 文章详情
     * @author: FengLei
     * @time: 2020/7/29 20:10
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function detail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric|exists:articles,id'
        ]);
        $article = Article::with('labels:id,name')->findOrFail($request->id);
        return Result::success($article);
    }

    /**
     * 发布文章
     * @author: FengLei
     * @time: 2020/7/29 17:55
     * @param ArticleRequest $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ArticleRequest $request, Article $article)
    {
        $article->title = $request->title;
        $article->content = $request->contents;
        $article->save();
        // 保存标签到中间表
        $article->labels()->syncWithoutDetaching($request->labels);
        return Result::success();
    }

    /**
     * 删除文章
     * @author: FengLei
     * @time: 2020/7/28 18:03
     * @param Article $article
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Article $article, Request $request)
    {
        $article = $article->findOrFail($request->id);
        $article->delete();
        return Result::success();
    }
}
