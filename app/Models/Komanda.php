<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komanda extends Model
{
    use HasFactory;

    public function vizualieMateriali(){
        return $this->hasMany(VizualaisMaterials::class, 'komandas_id');
    }

    public function speletajs(){
        return $this->hasMany(Speletajs::class);
        }
    public function speles(){
        return $this->hasMany(Speles::class, 'komanda_id');
        }    
    public function user()
    {
         return $this->belongsTo( related:User::class);
    }
    public function trenini(){
        return $this->hasMany(Trenins::class);
        }
}
