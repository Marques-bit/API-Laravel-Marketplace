<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    use Notifiable;

    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
