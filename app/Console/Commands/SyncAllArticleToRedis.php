<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SyncAllArticleToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:sync-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将所有文章ID同步到redis中';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Article::with('labels')
            ->chunk(10000, function ($articles) {
                foreach ($articles as $article) {
                    // 将要给或多个 member 元素及其 score 值加入到有序集 key 中
                    Redis::zAdd('articles_ids', strtotime($article->created_at), $article->id);
                    $this->info('正在同步 ID 为 '.$article->id.' 的文章');
                }
            });
        $this->info('同步完成');
    }
}
