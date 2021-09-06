<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // USER HAS MANY LOVERS
    public function lover(){
        return $this->hasMany(Lover::class);
    }

    // USER HAS MANY MESSAGES
    public function message(){
        return $this->hasMany(Message::class);
    }

    // USER HAS ONE HOBBIE TABLE
    public function hobbie(){
        return $this->hasOne(Hobbie::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nick',
        'name',
        'surname',
        'email',
        'age',
        'phone',
        'description',
        'urlpic',
        'country',
        'city',
        'cp',
        'gender',
        'sexuality',
        'lookingfor',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'isAdmin',
        'isActive',
        'isPremium'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
