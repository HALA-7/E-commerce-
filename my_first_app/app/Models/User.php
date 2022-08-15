<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; //for passport addition

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile_phone',
        // 'whatsapp_url',
        //'profile_img_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //the user will add products
    public function products(){
        return $this->hasMany(Product::class, 'user_id');
    }

    public function likes(){
       return $this->hasMany(Like::class,'user_id');
    }

    public function comments(){
         return $this->hasmany(Comment::class,'user_id');
    }

}
