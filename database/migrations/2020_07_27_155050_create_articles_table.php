<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', '50')->comment('文章标题');
            $table->text('content')->nullable()->comment('文章内容');
            $table->boolean('is_top')->default(0)->comment('是否置顶 0-否 1-是');
            $table->boolean('status')->default(1)->comment('0-下架 1-上架');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE articles COMMENT '文章表'");

        Schema::create('article_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->unique()->comment('标签名称');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE article_labels COMMENT '文章标签表'");

        Schema::create('article_has_labels', function (Blueprint $table) {
            $table->foreignId('article_id')
                ->references('id')
                ->on('articles')
                ->comment('文章ID');
            $table->foreignId('label_id')
                ->references('id')
                ->on('article_labels')
                ->comment('标签ID');

            $table->primary(['article_id', 'label_id'], 'article_has_labels_article_id_label_id_primary');
        });
        DB::statement("ALTER TABLE article_has_labels COMMENT '文章拥有的标签关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_has_labels');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_labels');
    }
}
