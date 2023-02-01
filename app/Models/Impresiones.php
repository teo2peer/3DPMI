<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impresiones extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'name',
        'description',
        'impresora_id',
        'filamento_id',
        'gcode_id',

        'estado',
        'puesto_por',
        'iniciado',
        'finalizado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function impresoras()
    {
        return $this->belongsTo(Impresoras::class, 'impresora_id', 'id');
    }

    public function filamentos()
    {
        return $this->belongsTo(Filamentos::class, 'filamento_id', 'id');
    }

    public function puestos_por()
    {
        return $this->belongsTo(User::class, 'puesto_por', 'id');
    }

    public function gcode()
    {
        return $this->belongsTo(Gcodes::class, 'gcode_id', 'id');
    }

}