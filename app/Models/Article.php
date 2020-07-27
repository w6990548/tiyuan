<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
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

    // 文章拥有的标签
    public function labels()
    {
        return $this->belongsToMany(
            'article_labels',
            'article_has_labels',
            'article_id',
            'label_id'
        );
    }
}
