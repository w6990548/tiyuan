<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * 可以被批量赋值的属性。
     * @var array
     */
    protected $fillable = ['title', 'content', 'is_top', 'status'];

    /**
     * 转换为原生属性
     * @var array
     */
    protected $casts = [
        'is_top' => 'boolean',
        'status' => 'boolean',
    ];

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
            'App\Models\ArticleLabel',
            'article_has_labels',
            'article_id',
            'label_id'
        );
    }
}
