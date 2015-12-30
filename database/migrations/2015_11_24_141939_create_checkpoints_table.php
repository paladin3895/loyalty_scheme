<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id')->unsigned();
            $table->integer('schema_id')->unsigned();
            $table->text('state');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entities')
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
        Schema::table('checkpoints', function (Blueprint $table) {
            $table->dropForeign('checkpoints_schema_id_foreign');
            $table->dropForeign('checkpoints_entity_id_foreign');
        });
        Schema::dropIfExists('checkpoints');
    }
}
