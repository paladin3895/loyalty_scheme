<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemaLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->unsigned();
            $table->integer('node_from')->unsigned();
            $table->integer('node_to')->unsigned();
            $table->text('config');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('schema_id')
                  ->references('id')
                  ->on('schemas')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('node_from')
                  ->references('id')
                  ->on('nodes')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('node_to')
                  ->references('id')
                  ->on('nodes')
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
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign('links_node_from_foreign');
            $table->dropForeign('links_node_to_foreign');
        });
        Schema::dropIfExists('links');
    }
}
