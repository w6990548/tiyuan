<?php

namespace Tests\Feature;

use App\Exceptions\BaseResponseException;
use App\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class RoleApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(AdminUser::class)->create();

        $this->user->assignRole(\App\Models\Role::ADMIN_ID);
    }

    /**
     * 创建角色
     * Created by FengLei on 2020/9/23 16:35
     */
    public function testCreateRole()
    {
        $data = [
            'guard_name' => 'api',
            'name' => 'test name'.\helpers::getRandomString(0, 4),
            'alias_name' => 'test alias'.\helpers::getRandomString(0, 4),
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/roles/create', $data);

        $assetData = [
            'guard_name' => 'api',
            'name' => $response->original['data']->name,
            'alias_name' => $response->original['data']->alias_name,
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assetData);
    }

    /**
     * 修改角色
     * Created by FengLei on 2020/9/23 16:51
     */
    public function testEditRole()
    {
        $role = $this->makeRole();

        $editData = [
            'id' => $role->id,
            'name' => 'edit name'.\helpers::getRandomString(0, 4),
            'alias_name' => 'edit alias'.\helpers::getRandomString(0, 4),
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/roles/edit', $editData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 0
            ]);
    }

    /**
     * 删除角色
     * Created by FengLei on 2020/9/23 16:57
     */
    public function testDelRole()
    {
        $role = $this->makeRole();

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/roles/delete', ['id' => $role->id]);
        $response->assertStatus(200);

        $response = Role::find($role->id);
        if ($response !== null) {
            throw new BaseResponseException('角色删除失败');
        }
    }

    /**
     * 新建角色
     * Created by FengLei on 2020/9/23 16:58
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makeRole()
    {
        return factory(Role::class)->create();
    }
}
