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
        Schema::create('impresoras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip')->default('0');
            $table->string('port')->default('0');
            // estado
            $table->boolean('estado')->default(true);
            // tipo de impresora
            $table->string('tipo')->default('FDM');
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