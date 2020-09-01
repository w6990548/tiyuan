<?php

use App\Models\Article;
use App\Models\ArticleLabel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Redis::del('articles_ids');
        // 填充文章带标签
        factory(Article::class, 50)->create()->each(function ($article) {
            // 同步数据到 redis 中
            Redis::zAdd('articles_ids', strtotime($article->created_at), $article->id);
            $article->labels()->save(factory(ArticleLabel::class)->make());
        });
    }
}
