<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Spatie\Permission\Models\Role as packageRole;

/**
 * Class Role
 * @package App\Models
 *
 * @property integer id
 * @property string name
 * @property string alias_name
 * @property string guard_name
 * @property string created_at
 * @property string updated_at
 */

class Role extends packageRole
{
    use SerializeDate;

    protected $fillable = ['name', 'alias_name', 'guard_name'];

    // 超级管理员角色
    const ADMIN = 'administrator';
    const ADMIN_ID = 1;
}
