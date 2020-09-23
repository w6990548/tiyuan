<?php

namespace Tests\Feature;

use App\Exceptions\BaseResponseException;
use App\Models\AdminUser;
use App\Models\Role;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class AdminUserApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(AdminUser::class)->create();

        $this->user->assignRole(Role::ADMIN_ID);
    }

    /**
     * 添加用户
     * Created by FengLei on 2020/9/23 17:05
     */
    public function testCreateAdminUser()
    {
        $data = [
            'username' => 'test'.\helpers::getRandomString(0, 6),
            'password' => '123456',
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/users/create', $data);

        $assetData = [
            'username' => $response->original['data']->username,
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assetData);
    }

    /**
     * 编辑用户
     * Created by FengLei on 2020/9/23 17:18
     */
    public function testEditAdminUser()
    {
        $adminUser = $this->makeAdminUser();

        $editData = [
            'id' => $adminUser->id,
            'username' => 'edit'.\helpers::getRandomString(0,6),
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/users/edit', $editData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 0
            ]);
    }

    /**
     * 删除用户
     * Created by FengLei on 2020/9/23 17:21
     */
    public function testDelAdminUser()
    {
        $adminUser = $this->makeAdminUser();

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/users/delete', ['id' => $adminUser->id]);
        $response->assertStatus(200);

        $response = AdminUser::find($adminUser->id);
        if ($response !== null) {
            throw new BaseResponseException('后台用户删除失败');
        }
    }

    /**
     * 重置密码
     * Created by FengLei on 2020/9/23 17:28
     */
    public function testResetPwd()
    {
        $adminUser = $this->makeAdminUser();

        $resetData = ['id' => $adminUser->id, 'password' => '123123'];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/users/reset', $resetData);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 0
            ]);
    }

    /**
     * 创建用户
     * Created by FengLei on 2020/9/23 17:18
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makeAdminUser()
    {
        return factory(AdminUser::class)->create();
    }
}
