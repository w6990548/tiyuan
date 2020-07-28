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
        factory(Article::class, 15)->create()->each(function ($article) {
            $article->labels()->save(factory(ArticleLabel::class)->make());
        });
    }
}
