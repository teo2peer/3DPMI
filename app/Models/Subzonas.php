<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subzonas extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'zona_id',
    ];
    public $timestamps = true;

    public function zona()
    {
        return $this->belongsTo(Zonas::class);
    }

}