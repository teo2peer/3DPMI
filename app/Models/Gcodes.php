<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gcodes extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'name',
        'path',
        'made_for',
        'filament_used',
        'time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function impresiones()
    {
        // this id is gcode_id in impresiones table
        return $this->hasMany(Impresiones::class, 'gcode_id', 'id');
    }


}