<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Models
 *
 * @property string key
 * @property string value
 * @method static create(array $array)
 */

class Setting extends Model
{
    /**
     * 可以被批量赋值的属性
     * @var string[]
     */
    protected $fillable = ['key', 'value'];

    /**
      开关项
     */
    const SWITCH_LIST = [
        'site_switch' // 网站开关
    ];
}
