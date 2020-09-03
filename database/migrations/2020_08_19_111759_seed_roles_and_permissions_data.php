<?php

use App\Models\AdminUser;
use App\Models\Menu;
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
        $sysPs = Permission::create([
            'guard_name' => 'api', 'name' => 'admin/system', 'slug' => 'menu-admin', 'alias_name' => '系统',
            'icon' => 'al-icon-guests', 'type' => \App\Models\Permission::TYPE_MENU]);
            $userPs = Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-users', 'alias_name' => '用户管理',
                'name' => 'admin/users', 'icon' => 'al-icon-addresslist', 'parent_id' => $sysPs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);
                Permission::create([
                    'guard_name' => 'api', 'slug' => 'api-users-list', 'alias_name' => '用户列表',
                    'name' => 'api/admin/users', 'parent_id' => $userPs->id,
                    'type' => \App\Models\Permission::TYPE_API]);
            Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-roles', 'alias_name' => '角色管理',
                'name' => 'admin/roles', 'icon' => 'al-icon-groupctrl', 'parent_id' => $sysPs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);
            Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-permissions', 'alias_name' => '权限管理',
                'name' => 'admin/permissions', 'icon' => 'al-icon-safety', 'parent_id' => $sysPs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);
            Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-system-setting', 'alias_name' => '参数配置',
                'name' => 'admin/system-setting', 'icon' => 'al-icon-mirrorlightctrl', 'parent_id' => $sysPs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);
        // 先创建权限
        $articlePs = Permission::create([
            'guard_name' => 'api', 'name' => 'admin/article', 'slug' => 'menu-articles', 'alias_name' => '文章管理',
            'icon' => 'al-icon-instruction', 'type' => \App\Models\Permission::TYPE_MENU]);
            $artPs = Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-articles-list', 'alias_name' => '文章列表',
                'name' => 'admin/articles', 'icon' => 'al-icon-schedule', 'parent_id' => $articlePs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);
                Permission::create([
                    'guard_name' => 'api', 'slug' => 'page-release-article', 'alias_name' => '发布文章',
                    'name' => 'admin/articles/create', 'parent_id' => $artPs->id,
                    'type' => \App\Models\Permission::TYPE_PAGE]);
                Permission::create([
                    'guard_name' => 'api', 'slug' => 'page-edit-article', 'alias_name' => '编辑文章',
                    'name' => 'admin/articles/edit', 'parent_id' => $artPs->id,
                    'type' => \App\Models\Permission::TYPE_PAGE]);
                Permission::create([
                    'guard_name' => 'api', 'slug' => 'page-detail-article', 'alias_name' => '文章详情',
                    'name' => 'admin/articles/detail', 'parent_id' => $artPs->id,
                    'type' => \App\Models\Permission::TYPE_PAGE]);
            Permission::create([
                'guard_name' => 'api', 'slug' => 'menu-labels-list', 'alias_name' => '文章标签',
                'name' => 'admin/labels', 'icon' => 'al-icon-privacy', 'parent_id' => $articlePs->id,
                'type' => \App\Models\Permission::TYPE_MENU]);

        // 创建站长角色
        Role::create(['guard_name' => 'api', 'name' => AdminUser::ADMIN, 'alias_name' => '超级管理员']);

        // 创建管理员角色，并赋予权限
        // $maintainer = Role::create(['guard_name' => 'api', 'slug' => 'admin', 'alias_name' => '管理员']);
        // $maintainer->givePermissionTo('admin');
        // $maintainer->givePermissionTo('admin/users');
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
        DB::table('menu')->delete();
        Model::reguard();
    }
}
