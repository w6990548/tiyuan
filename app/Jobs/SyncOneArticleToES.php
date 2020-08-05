<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOneArticleToES implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;
    protected $operation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article, $operation)
    {
        $this->article = $article;
        $this->operation = $operation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 获取 Elasticsearch 对象
        $es = app('es');
        $data = $this->article->toESArray();

        if ($this->operation === 'delete') {
            $es->delete(['index' => 'articles', 'id' => $data['id'],]);
        } else {
            // 同步数据到 Elasticsearch
            $es->index(['index' => 'articles', 'id' => $data['id'], 'body' => $data,]);
        }
    }
}
