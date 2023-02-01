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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('gravedad');
            $table->integer('estado');
            $table->bigInteger('impresora')->unsigned()->nullable();
            $table->bigInteger('puesto_por')->unsigned();
            $table->bigInteger('resuelto_por')->unsigned();
            $table->timestamps();

            // $table->foreign('impresora')->references('id')->on('impresoras');
            // $table->foreign('puesto_por')->references('id')->on('users');
            // $table->foreign('resuelto_por')->references('id')->on('users');

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
};