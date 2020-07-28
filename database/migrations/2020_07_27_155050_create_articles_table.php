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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
