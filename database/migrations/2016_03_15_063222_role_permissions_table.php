<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('admin_roles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->tinyInteger('is_del')->default(0)->comment('是否删除;1为已删除');
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('admin_permissions', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('fid')->unsigned()->default(0)->comment('菜单父ID');
            $table->string('icon')->nullable()->comment('图标class');
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('is_menu')->default(0)->comment('是否作为菜单显示,[1|0]');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('admin_permission_roles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('admin_permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('admin_roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permission_roles');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
    }
}
