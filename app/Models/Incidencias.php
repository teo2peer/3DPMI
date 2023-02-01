<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencias extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'gravedad', 
        'estado',
        'impresora',
        'puesto_por',
        'resuelto_por',
    ];

    public function impresoras()
    {
        return $this->belongsTo(Impresoras::class, 'impresora', 'id');
    }

    public function puestos_por()
    {
        return $this->belongsTo(User::class, 'puesto_por', 'id');
    }

    public function resueltos_por()
    {
        return $this->belongsTo(User::class, 'resuelto_por', 'id');
    }

}