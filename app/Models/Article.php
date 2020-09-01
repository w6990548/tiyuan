<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * Class Article
 * @package App\Models
 *
 * @property integer id
 * @property string title
 * @property string content
 * @property boolean is_top
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */

class Article extends Model
{
    use SoftDeletes;
    use SerializeDate;

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

    /**
     * TODO esearch 使用
     * @author: FengLei
     * @time: 2020/8/27 10:19
     * @return array
     */
    public function toESArray()
    {
        $arr = Arr::only($this->toArray(), [
            'id',
            'title',
            'content',
            'is_top',
            'status',
            'created_at'
        ]);

        $arr['labels'] = $this->labels->map(function (ArticleLabel $articleLabel) {
            return Arr::only($articleLabel->toArray(), ['id', 'name']);
        });

        return $arr;
    }
}
