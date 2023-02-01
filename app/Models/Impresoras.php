<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impresoras extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip',
        'port', 
        'estado',
        'tipo',
    ];
    // disable timestamps
    public $timestamps = false;
    // Una impresora puede tener muchas impresiones

    function impresiones(){
        return $this->hasMany(Impresiones::class, 'impresora', 'id');
    }
}