<?php

namespace Tests\Feature;

use App\Exceptions\BaseResponseException;
use App\Models\AdminUser;
use App\Models\ArticleLabel;
use App\Models\Role;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

/**
 * 文章标签模块测试
 * Class ArticleLabelApiTest
 * @package Tests\Feature
 */
class ArticleLabelApiTest extends TestCase
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
     * 创建标签
     * Created by FengLei on 2020/9/23 11:45
     */
    public function testCreateArticleLabel()
    {
        $data = ['name' => 'test name'];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/labels/create', $data);

        $assertData = ['name' => 'test name'];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    /**
     * 删除标签
     * Created by FengLei on 2020/9/23 12:23
     */
    public function testDelArticleLabel()
    {
        $articleLabel = factory(ArticleLabel::class)->create();

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/labels/delete', ['id' => $articleLabel->id]);
        $response->assertStatus(200);

        $response = ArticleLabel::find($articleLabel->id);
        if ($response !== null) {
            throw new BaseResponseException('标签删除失败');
        }
    }
}
