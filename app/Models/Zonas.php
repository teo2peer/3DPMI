<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zonas extends Model
{
    use HasFactory;

    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];
    public $timestamps = true;


}