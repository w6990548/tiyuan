<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleLabel
 * @package App\Models
 *
 * @property integer id
 * @property string name
 * @property string created_at
 * @property string updated_at
 */

class ArticleLabel extends Model
{
    use SerializeDate;

    /**
     * 可以被批量赋值的属性。
     * @var array
     */
    protected $fillable = ['name'];

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
