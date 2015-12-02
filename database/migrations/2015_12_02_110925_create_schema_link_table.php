<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemaLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->unsigned();
            $table->integer('node_from')->unsigned();
            $table->integer('node_to')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('schema_id')
                  ->references('id')
                  ->on('schema')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('node_from')
                  ->references('id')
                  ->on('node')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('node_to')
                  ->references('id')
                  ->on('node')
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
        //
    }
}
