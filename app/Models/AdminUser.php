<?php

namespace App\Models;

use App\Traits\SerializeDate;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class AdminUser
 * @package App\Models
 *
 * @property integer id
 * @property string username
 * @property string password
 * @property string created_at
 * @property string updated_at
 */

class AdminUser extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;
    use SerializeDate;

    protected $guard_name = 'api';

    const ADMIN = 'administrator';
    const ADMIN_ID = 1;

    /**
     * 可以被批量赋值的属性。
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // 是否超级管理员
    public static function isAdmin($adminUser)
    {
        return $adminUser->hasRole(self::ADMIN);
    }

    // 获取管理员
    public function findById($id)
    {
        return AdminUser::findOrFail($id);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
