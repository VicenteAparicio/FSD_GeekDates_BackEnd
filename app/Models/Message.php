<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // RELATION TO USER
    public function user(){
        return $this->belongsTo(User::class);
    }

    // RELATION TO MATCH
    public function lover(){
        return $this->belongsTo(Lover::class);
    }

    protected $fillable = [
        'text',
        'date',
        'match_id',
        'user_from_id',
        'user_to_id'
    ];

    // protected $hidden = [
    //     'isActive',
    // ];
}
