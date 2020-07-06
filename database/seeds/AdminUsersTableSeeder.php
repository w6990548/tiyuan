<?php

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $user->password = Hash::make('admins');
        $user->save();

        // 初始化用户角色，将1号用户指定为站长
        $user->assignRole(AdminUser::ADMIN);

        // 2号用户指定为管理员
        $user = AdminUser::find(2);
        $user->username = 'fenglei';
	    $user->password = Hash::make('123456');
        $user->save();
        $user->assignRole('guanliyuan');
    }
}
