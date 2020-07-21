<?php

namespace App\Models;

use DateTimeInterface;
use Spatie\Permission\Models\Role as packageRole;

/**
 * Class Role
 * @package App\Models
 *
 * @property string name
 * @property string guard_name
 */

class Role extends packageRole
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
}