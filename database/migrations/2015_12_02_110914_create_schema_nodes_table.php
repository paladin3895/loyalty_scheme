<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemaNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->unsigned();
            $table->text('attributes');
            $table->text('policies');
            $table->text('rewards');
            $table->text('config');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign('nodes_schema_id_foreign');
        });
        Schema::dropIfExists('nodes');
    }
}
