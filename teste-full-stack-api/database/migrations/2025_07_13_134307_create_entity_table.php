<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('corporate_reason', 255);
            $table->string('fantasy_name', 255);
            $table->char('cnpj', 14);
            $table->date('opening_date');
            $table->boolean('active')->default(true);

            $table->unsignedInteger('regionalId');
            $table->foreign('regionalId')->references('id')->on('regional')->onDelete('cascade');
   
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
        Schema::dropIfExists('entity');
    }
}
