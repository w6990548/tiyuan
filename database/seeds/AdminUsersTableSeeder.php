<?php

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(AdminUser::class)->times(50)->make();
        AdminUser::insert($user->makeVisible(['password'])->toArray());

        $user = AdminUser::find(1);
        $user->username = 'admin';
        $user->save();

        // 初始化用户角色，将1号用户指定为站长
        $user->assignRole('admin');

        // 2号人户指定为管理员
        $user = AdminUser::find(2);
        $user->assignRole('fenglei');
    }
}
