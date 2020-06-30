<?php

namespace App\Models;

use DateTimeInterface;
use Spatie\Permission\Models\Permission as packagePermission;

/**
 * Class Permission
 * @package App\Models
 *
 * @property string name
 * @property string purview_name
 * @property integer level
 * @property string guard_name
 * @property int pid
 * @property string created_at
 * @property string updated_at
 */

class Permission extends packagePermission
{

	protected $fillable = ['name', 'purview_name'];

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

    public function childPermission()
    {
        return $this->hasMany(Permission::class, 'pid', 'id');
    }
}
