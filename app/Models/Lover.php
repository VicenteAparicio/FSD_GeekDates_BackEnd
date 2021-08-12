<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lover extends Model
{
    use HasFactory;

    // RELATION TO USER
    public function user(){
        return $this->belongsTo(User::class);
    }

    // RELATION TO MESSAGE
    public function message(){
        return $this->hasMany(Message::class);
    }

    

    protected $fillable = [
        'user_a_id',
        'user_b_id'
    ];

    protected $hidden = [
        'isActive',
    ];
    
}
