<?php

namespace App\Models;

use DateTimeInterface;
use Spatie\Permission\Models\Permission as packagePermission;

class Permission extends packagePermission
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

    public function childPermission()
    {
        return $this->hasMany(Permission::class, 'pid', 'id');
    }
}
