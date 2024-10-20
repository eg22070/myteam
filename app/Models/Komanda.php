<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komanda extends Model
{
    use HasFactory;

    public function pazinojums(){
        return $this->hasMany(Pazinojums::class);
    }
    public function speletajs(){
        return $this->hasMany(Speletajs::class);
        }
    public function user()
    {
         return $this->belongsTo( related:User::class);
    }
}
