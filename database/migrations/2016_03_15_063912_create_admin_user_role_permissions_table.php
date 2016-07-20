<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_roles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->integer('admin_user_id')->unsigned();
            $table->foreign('admin_user_id')->references('id')->on('admin_users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('admin_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['admin_user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_role');
    }
}
