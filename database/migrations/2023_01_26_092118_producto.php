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
        Schema::create('productos', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->float('precio')->default(0);
            $table->bigInteger('zona_id')->unsigned();
            $table->bigInteger('subzona_id')->unsigned();
            $table->bigInteger('categoria_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('zona_id')->references('id')->on('zonas');
            $table->foreign('subzona_id')->references('id')->on('subzonas');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('user_id')->references('id')->on('users');
            
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