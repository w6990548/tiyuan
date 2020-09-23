<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Article;
use App\Models\Role;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

/**
 * 文章模块测试
 * Class ArticleApiTest
 * @package Tests\Feature
 */
class ArticleApiTest extends TestCase
{
    use ActingJWTUser;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(AdminUser::class)->create();

        // 添加角色
        $this->user->assignRole(Role::ADMIN_ID);
    }

    /**
     * 发布文章
     * Created by FengLei on 2020/9/17 17:10
     */
    public function testCreateArticle()
    {
        $data = ['title' => 'test title', 'content' => 'test content', 'labels' => [4, 5]];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/articles/create', $data);

        $assertData = [
            'title' => "test title",
            'content' => "test content",
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    /**
     * 编辑文章
     * Created by FengLei on 2020/9/17 17:29
     */
    public function testUpdateArticle()
    {
        $article = $this->makeArticle();

        $editData = ['id' => $article->id, 'title' => 'edit title', 'content' => 'edit content', 'labels' => [5]];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/articles/edit', $editData);

        $response->assertStatus(200);
    }

    /**
     * 文章详情
     * Created by FengLei on 2020/9/17 17:39
     */
    public function testArticleDetail()
    {
        $article = $this->makeArticle();

        $response = $this->JWTActingAs($this->user)
            ->json('GET', 'api/admin/articles/detail', ['id' => $article->id]);

        $assertData = [
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'status' => true,
            'is_top' => false,
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    /**
     * 删除文章
     * Created by FengLei on 2020/9/17 18:11
     */
    public function testDeleteArticle()
    {
        $article = $this->makeArticle();

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/articles/delete', ['id' => $article->id]);
        $response->assertStatus(200)
            ->assertJsonFragment([
            'code' => 0
            ]);

        // 重新请求详情，出现 10010 即可
        $response = $this->json('GET', 'api/admin/articles/detail', ['id' => $article->id]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 10010
            ]);
    }

    /**
     * 文章上下架、置顶
     * Created by FengLei on 2020/9/17 17:10
     */
    public function testArticleChange()
    {
        $article = $this->makeArticle();
        $data = ['id' => $article->id, 'is_top' => true, 'status' => false];

        $response = $this->JWTActingAs($this->user)
            ->json('POST', 'api/admin/articles/changeStatus', $data);

        $assertData = [
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'is_top' => true,
            'status' => false,
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    /**
     * 创建文章
     * Created by FengLei on 2020/9/17 17:29
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function makeArticle()
    {
        $article = factory(Article::class)->create();
        // 同步数据到 redis 中
        Redis::zAdd('articles_ids', strtotime($article->created_at), $article->id);
        return $article;
    }
}
