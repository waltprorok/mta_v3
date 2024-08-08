<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    const TABLE_NAME = 'messages';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id_from')->unsigned();
            $table->foreign('user_id_from')->references('id')->on('users');
            $table->integer('user_id_to')->unsigned();
            $table->foreign('user_id_to')->references('id')->on('users');
            $table->string('subject');
            $table->text('body');
            $table->boolean('read');
            $table->boolean('deleted');
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
