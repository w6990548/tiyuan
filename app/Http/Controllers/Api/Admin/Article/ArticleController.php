<?php

namespace App\Http\Controllers\Api\Admin\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleRequest;
use App\Models\Article;
use App\Result;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        // 返回有序集 key 中，指定区间内的成员。
        // 其中成员的位置按 score值（created_at）递减(从大到小)来排列
        $articleIds = Redis::zRevRange(
            'articles_ids',
            $request->pageSize * ($request->page - 1),
            $request->page * $request->pageSize - 1,
            'WITHSCORES'
        );
        // 返回有序集 key 的基数
        $total = Redis::zCard('articles_ids');

        $query = Article::with('labels')->orderByDesc('created_at');

        if ($articleIds) {
            $articles = $query->whereIn('id', $articleIds)->get();
            return Result::success([
                'list' => $articles,
                'total' => $total
            ]);
        }

        $articles = $query->paginate($request->pageSize);
        return Result::success([
            'list' => $articles->items(),
            'total' => $articles->total()
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
        DB::transaction(function () use ($article, $request) {
            $article->title = $request->get('title');
            $article->content = $request->get('content');
            $article->save();
            // 保存标签到中间表
            $article->labels()->sync($request->labels);
            // 同步数据到 redis 中
            Redis::zAdd('articles_ids', strtotime($article->created_at), $article->id);
        });

        return Result::success($article);
    }

    /**
     * 编辑文章
     * @author: FengLei
     * @time: 2020/7/30 17:44
     * @param ArticleRequest $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(ArticleRequest $request, Article $article)
    {
        DB::transaction(function () use ($article, $request) {
            $article = $article->findOrFail($request->id);
            $article->update($request->all());
            // 更新标签到中间表
            $article->labels()->sync($request->labels);
        });
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
    public function delete(Request $request)
    {
        DB::transaction(function () use ($request) {
            $article = Article::findOrFail($request->id);
            $article->delete();
            // 从 redis 中移除该文章
            Redis::zRem('articles_ids', $article->id);
        });
        return Result::success();
    }

    /**
     * 文章置顶、上下架
     * @author: FengLei
     * @time: 2020/7/30 14:43
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $article = Article::findOrFail($request->id);
        $article->update($request->all());
        return Result::success($article);
    }
}
