<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('account');
            $table->string('real_name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('salt', 60)->comment('密码加密随机字符串');
            $table->tinyInteger('is_super')->default(0)->comment('是否超级管理员;1是超级管理员');
            $table->tinyInteger('is_del')->default(0)->comment('是否删除;1为已删除');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_users');
    }
}
