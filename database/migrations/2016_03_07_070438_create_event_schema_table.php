<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_schema', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->integer('priority');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('schema_id')
                  ->references('id')
                  ->on('schemas')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_schema');
    }
}
