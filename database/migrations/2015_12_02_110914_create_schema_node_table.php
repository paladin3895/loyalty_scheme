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
            $table->text('policy');
            $table->text('reward');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('schema_id')
                  ->references('id')
                  ->on('schema')
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
        Schema::table('node', function (Blueprint $table) {
            $table->dropForeign('node_schema_id_foreign');
        });
        Schema::dropIfExists('node');
    }
}
