<?php

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 需清除缓存，否则会报错
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 先创建权限
        Permission::create(['guard_name' => 'api', 'name' => 'api/admin', 'purview_name' => '权限']);
        Permission::create(['guard_name' => 'api', 'name' => 'api/admin/users', 'purview_name' => '用户管理']);
        Permission::create(['guard_name' => 'api', 'name' => 'api/admin/roles', 'purview_name' => '角色管理']);
        Permission::create(['guard_name' => 'api', 'name' => 'api/admin/permissions', 'purview_name' => '权限管理']);
        Permission::create(['guard_name' => 'api',
            'name' => 'api/admin/permissions/create',
            'purview_name' => '添加权限', 'pid' => 4,
            'level' => 3,
        ]);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['guard_name' => 'api', 'name' => AdminUser::ADMIN]);
        $founder->givePermissionTo('api/admin');
        $founder->givePermissionTo('api/admin/users');
        $founder->givePermissionTo('api/admin/roles');
        $founder->givePermissionTo('api/admin/permissions');
        $founder->givePermissionTo('api/admin/permissions/create');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['guard_name' => 'api', 'name' => 'guanliyuan']);
        $maintainer->givePermissionTo('api/admin/permissions');

        // 一个一级，三个二级
        Permission::whereIn('id', [2, 3, 4])->update([
            'level' => 2,
            'pid' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 需清除缓存，否则会报错
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
