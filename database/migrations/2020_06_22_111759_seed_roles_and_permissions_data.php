<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        Permission::create(['name' => 'api/admin', 'purview_name' => '权限']);
        Permission::create(['name' => 'api/admin/users', 'purview_name' => '用户管理']);
        Permission::create(['name' => 'api/admin/roles', 'purview_name' => '角色管理']);
        Permission::create(['name' => 'api/admin/permissions', 'purview_name' => '权限管理']);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'admin']);
        $founder->givePermissionTo('api/admin');
        $founder->givePermissionTo('api/admin/users');
        $founder->givePermissionTo('api/admin/roles');
        $founder->givePermissionTo('api/admin/permissions');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'fenglei']);
        $maintainer->givePermissionTo('api/admin/roles');

        // 一个一级，三个二级
        Permission::whereIn('id', [2,3,4])->update([
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
