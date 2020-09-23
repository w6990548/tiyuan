<?php

namespace Tests\Feature;

use App\Exceptions\BaseResponseException;
use App\Models\AdminUser;
use App\Models\Role;
use helpers;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

/**
 * 权限测试（菜单、页面、api）
 * Class PermissionApiTest
 * @package Tests\Feature
 */
class PermissionApiTest extends TestCase
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
     * 添加权限
     * Created by FengLei on 2020/9/23 14:22
     */
    public function testCreatePermission()
    {
        $data = [
            'guard_name' => 'api',
            'name' => 'test name'.helpers::getRandomString(0,6),
            'slug' => 'test slug'.helpers::getRandomString(0,6),
            'type' => \App\Models\Permission::TYPE_MENU,
            'alias_name' => '测试权限',
            'parent_id' => 0,
            'icon' => 'al-icon-record'
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/permissions/create', $data);

        $assetData = [
            'guard_name' => 'api',
            'name' => $response->original['data']->name,
            'slug' => $response->original['data']->slug,
            'type' => \App\Models\Permission::TYPE_MENU,
            'alias_name' => '测试权限',
            'parent_id' => 0,
            'icon' => 'al-icon-record'
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assetData);
    }

    /**
     * 编辑权限
     * Created by FengLei on 2020/9/23 14:22
     */
    public function testEditPermission()
    {
        $permission = $this->makePermission();

        $editData = [
            'id' => $permission->id,
            'name' => 'edit name'.helpers::getRandomString(0,6),
            'slug' => 'edit slug'.helpers::getRandomString(0,6),
            'type' => \App\Models\Permission::TYPE_PAGE,
            'alias_name' => '修改权限',
            'parent_id' => 0,
            'icon' => 'al-icon-instruction'
        ];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/permissions/edit', $editData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 0
            ]);
    }

    /**
     * 删除权限
     * Created by FengLei on 2020/9/23 14:22
     */
    public function testDelPermission()
    {
        $permission = $this->makePermission();

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/permissions/delete', ['id' => $permission->id]);
        $response->assertStatus(200);

        $response = Permission::find($permission->id);
        if ($response !== null) {
            throw new BaseResponseException('权限删除失败');
        }
    }

    /**
     * 创建权限
     * Created by FengLei on 2020/9/23 14:46
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makePermission()
    {
        return factory(Permission::class)->create();
    }
}
