<?php

use App\Models\Article;
use App\Models\ArticleLabel;
use Illuminate\Database\Seeder;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充文章带标签
        // factory(Article::class, 500)->create()->each(function ($article) {
        //     $article->labels()->save(factory(ArticleLabel::class)->make());
        // });

        // 只填充文章
        $user = factory(Article::class)->times(20000)->make();
        Article::insert($user->toArray());
    }
}
