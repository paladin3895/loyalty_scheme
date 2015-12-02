<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemaNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->unsigned();
            $table->integer('policy_id')->unsigned();
            $table->integer('privilege_id')->unsigned();
            $table->text('policy_config');
            $table->text('privilege_context');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('schema_id')
                  ->references('id')
                  ->on('schema')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('policy_id')
                  ->references('id')
                  ->on('policy')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('privilege_id')
                  ->references('id')
                  ->on('privilege')
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
        Schema::dropIfExists('node');
    }
}
