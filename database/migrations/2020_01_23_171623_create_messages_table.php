<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('messageable_id');
            $table->string('messageable_type');
            $table->timestamps();

            $table
                ->foreign('sender_id')
                ->on('users')
                ->references('id')
                ->onDelete('SET NULL');
            $table
                ->foreign('receiver_id')
                ->on('users')
                ->references('id')
                ->onDelete('SET NULL');

            $table->index(['sender_id', 'receiver_id']);
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
            Schema::table('messages', function (Blueprint $table) {
                $table->dropForeign(['sender_id', 'receiver_id']);
            });
        }
        Schema::dropIfExists('sends');
    }
}
