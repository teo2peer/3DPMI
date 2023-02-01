<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impresiones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->unique();
            $table->string('name');
            $table->string('description');
            $table->bigInteger('impresora')->unsigned();
            $table->bigInteger('filamento')->unsigned();
            $table->bigInteger('gcode')->unsigned();
            
            $table->string('estado');
            $table->bigInteger('puesto_por')->unsigned()->default(0);
            $table->datetime('iniciado')->nullable();
            $table->datetime('finalizado')->nullable();
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('impresora')->references('id')->on('impresoras')->onDelete('cascade');
            // $table->foreign('filamento')->references('id')->on('filamentos')->onDelete('cascade');
            // $table->foreign('puesto_por')->references('id')->on('users')->onDelete('cascade');


        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('impresiones');
    }
};