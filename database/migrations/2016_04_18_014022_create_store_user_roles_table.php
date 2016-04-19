<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_user_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('store_user_id')->unsigned();
            $table->foreign('store_user_id')->references('id')->on('store_users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('admin_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['store_user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_user_role');
    }
}
