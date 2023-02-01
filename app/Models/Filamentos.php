<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filamentos extends Model
{
    use HasFactory;


    // $table->id();
    // $table->bigInteger('user_id')->unsigned()->unique();
    // $table->string('name');

    protected $fillable = [
        'user_id',
        'name',
    ];

    // disable timestamps
    public $timestamps = false;
    //disable update at and created at
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}