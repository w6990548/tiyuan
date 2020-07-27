<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->comment('标签名称');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE article_labels COMMENT '文章标签表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_labels');
    }
}
