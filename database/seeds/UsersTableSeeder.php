<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->times(50)->make();
        User::insert($user->makeVisible(['password'])->toArray());

        $user = User::find(1);
        $user->username = 'admin';
        $user->save();
    }
}
