<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityPrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_privilege', function (Blueprint $table) {
            $table->integer('entity_id')->unsigned();
            $table->integer('privilege_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entity')
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
        Schema::dropIfExists('entity_privilege');
    }
}
