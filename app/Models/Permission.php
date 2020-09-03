<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Spatie\Permission\Models\Permission as packagePermission;

/**
 * Class Permission
 * @package App\Models
 *
 * @property integer id
 * @property string name
 * @property string alias_name
 * @property string slug
 * @property string icon
 * @property integer type
 * @property integer parent_id
 * @property string guard_name
 * @property string created_at
 * @property string updated_at
 */

class Permission extends packagePermission
{
    use SerializeDate;

	protected $fillable = ['name', 'alias_name', 'slug', 'type', 'icon', 'parent_id', 'guard_name'];

    // 权限类型 默认1-菜单 2-api接口 3-页面元素
    const TYPE_MENU = 1;
    const TYPE_API = 2;
    const TYPE_PAGE = 3;
}
