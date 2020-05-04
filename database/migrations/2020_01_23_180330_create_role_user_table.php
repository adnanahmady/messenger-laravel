<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('CASCADE');
            $table
                ->foreign('role_id')
                ->on('roles')
                ->references('id')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! app()->environment('testing')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropForeign(['role_id', 'user_id']);
            });
        }
        Schema::dropIfExists('role_user');
    }
}
