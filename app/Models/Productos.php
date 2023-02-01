<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    // $table->id();
    // $table->string('name');
    // $table->string('descripcion');
    // $table->integer('cantidad');
    // $table->float('precio')->default(0);
    // $table->bigInteger('zona_id')->unsigned();
    // $table->bigInteger('subzona_id')->unsigned();
    // $table->bigInteger('categoria_id')->unsigned();
    // $table->bigInteger('user_id')->unsigned();
    // $table->timestamps();
    
    // $table->foreign('zona_id')->references('id')->on('zonas');
    // $table->foreign('subzona_id')->references('id')->on('subzonas');
    // $table->foreign('categoria_id')->references('id')->on('categorias');
    // $table->foreign('user_id')->references('id')->on('users');

    protected $fillable = [
        'name',
        'descripcion',
        'cantidad',
        'precio',
        'zona_id',
        'subzona_id',
        'categoria_id',
        'user_id',
    ];

    // public timestamps = true;

    public function zona()
    {
        return $this->belongsTo('App\Models\Zonas');
    }

    public function subzona()
    {
        return $this->belongsTo('App\Models\Subzonas');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categorias');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    
}