<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobbie extends Model
{
    use HasFactory;

    // RELATION TO USER
    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'tablegames',
        'rolegames',
        'videogames',
        'cosplay',
        'anime'
    ];
}
