<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('后台用户ID');
            $table->string('path')->comment('请求地址');
            $table->string('method', '10')->comment('请求方式');
            $table->string('ip' )->comment('IP地址');
            $table->text('input')->comment('请求参数内容');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE admin_logs COMMENT '系统日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
}
