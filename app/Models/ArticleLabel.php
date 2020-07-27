<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLabel extends Model
{
    // 拥有此标签的文章
    public function articles()
    {
        return $this->belongsToMany(
            'articles',
            'article_has_labels',
            'label_id',
            'article_id'
        );
    }
}
