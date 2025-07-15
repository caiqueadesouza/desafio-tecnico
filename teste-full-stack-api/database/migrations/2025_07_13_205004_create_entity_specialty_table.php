<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitySpecialtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_specialty', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entityId');
            $table->unsignedInteger('specialtyId');
            $table->foreign('entityId')->references('id')->on('entity')->onDelete('cascade');
            $table->foreign('specialtyId')->references('id')->on('specialty')->onDelete('cascade');
            $table->unique(['entityId', 'specialtyId']);
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
        Schema::dropIfExists('entity_specialty');
    }
}
