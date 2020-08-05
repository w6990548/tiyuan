<?php

namespace App\Console\Commands\Elasticsearch;

use App\Models\Article;
use Illuminate\Console\Command;

class SyncArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:sync-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将文章数据同步到 Elasticsearch';

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
        // 获取 Elasticsearch 对象
        $es = app('es');

        Article::query()
            ->where('id', '>', '750154')
            // 预加载标签数据，避免 n+1
            ->with('labels')
            // 使用 chunkById 避免一次性加载过多数据
            ->chunkById(10000, function ($articles) use ($es) {
                $this->info(sprintf('正在同步 ID 范围为 %s 至 %s 的商品', $articles->first()->id, $articles->last()->id));

                // 初始化请求体
                $req = ['body' => []];
                // 遍历文章
                foreach ($articles as $article) {
                    // 将文章模型转为 Elasticsearch 所用的数组
                    $data = $article->toESArray();

                    $req['body'][] = [
                        'index' => [
                            '_index' => 'articles',
                            '_id' => $data['id'],
                        ]
                    ];
                    $req['body'][] = $data;
                }
                try {
                    // 使用 bulk 方法批量创建
                    $es->bulk($req);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            });
        $this->info('同步完成');
    }
}
