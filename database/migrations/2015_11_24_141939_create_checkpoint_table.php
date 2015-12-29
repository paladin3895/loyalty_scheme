<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id')->unsigned();
            $table->integer('schema_id')->unsigned();
            $table->text('state');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entity')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
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
        Schema::table('checkpoint', function (Blueprint $table) {
            $table->dropForeign('checkpoint_schema_id_foreign');
            $table->dropForeign('checkpoint_entity_id_foreign');
        });
        Schema::dropIfExists('checkpoint');
    }
}
