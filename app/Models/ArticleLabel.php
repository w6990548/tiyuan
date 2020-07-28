<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ArticleLabel extends Model
{
    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    // 拥有此标签的文章
    public function articles()
    {
        return $this->belongsToMany(
            'App\Models\Article',
            'article_has_labels',
            'label_id',
            'article_id'
        );
    }
}
