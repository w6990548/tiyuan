<?php

namespace App\Http\Controllers\Api\Admin\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleRequest;
use App\Models\Article;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        // 其中成员的位置按 score 值递减(从大到小)来排列
        $articles = Redis::zRevRange(
            'articles',
            $request->pageSize * ($request->page - 1),
            $request->page * $request->pageSize - 1,
            'WITHSCORES'
        );
        // 返回有序集 key 的基数
        $total = Redis::zCard('articles');

        if ($articles) {
            foreach ($articles as &$article) {
                $article = json_decode($article);
            }
            // 将多维数组中数组的值取出平铺为一维数组
            $articles = Arr::flatten($articles);
        } else {
            $articles = Article::with('labels')
                ->orderByDesc('id')
                ->skip($request->pageSize * ($request->page - 1))
                ->take($request->pageSize)
                ->get();

            foreach ($articles as $article) {
                // 将要给或多个 member 元素及其 score 值加入到有序集 key 中
                Redis::zAdd('articles', $article->id, json_encode($article));
            }
        }

        return Result::success([
            'list' => $articles,
            'total' => $total
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
        $article->labels()->sync($request->labels);

        // 同步数据到 redis 中
        Redis::zAdd('articles', $article->id, json_encode($article));

        // 同步数据到 Elasticsearch
        // $this->dispatch(new SyncOneArticleToES($article, 'create'));
        return Result::success();
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
        $article = $article->findOrFail($request->id);
        $article->update($request->all());
        // 更新标签到中间表
        $article->labels()->sync($request->labels);

        // 同步数据到 redis 中
        Redis::zAdd('articles', $article->id, json_encode($article));

        // 同步数据到 Elasticsearch
        // $this->dispatch(new SyncOneArticleToES($article, 'update'));
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
        $article = Article::findOrFail($request->id);
        $article->delete();

        // 从 redis 中移除该文章
        Redis::zRem('articles', $article->id);

        // 同步数据到 Elasticsearch
        // $this->dispatch(new SyncOneArticleToES($article, 'delete'));
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

        // 同步数据到 redis 中
        Redis::zAdd('articles', $article->id, json_encode($article));

        // 同步数据到 Elasticsearch
        // $this->dispatch(new SyncOneArticleToES($article, 'update'));
        return Result::success();
    }
}
