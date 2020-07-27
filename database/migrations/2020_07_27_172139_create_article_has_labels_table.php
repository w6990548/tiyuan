<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleHasLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
