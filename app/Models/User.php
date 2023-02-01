<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified',
        'google_id',
        'rol',

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function add($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->google_id = $data['google_id'];
        $user->email = $data['email'];
        $user->email_verified = $data['email_verified'];
        $user->rol = $data['rol'];

        $user->save();
        return $user;
    }

    // user have many impresiones
    public function impresiones()
    {
        return $this->hasMany(Impresiones::class, 'user_id', 'id');
    }

    // user have many filamentos
    public function filamentos()
    {
        return $this->hasMany(Filamentos::class, 'user_id', 'id');
    }

    

    
}