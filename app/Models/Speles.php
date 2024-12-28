<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speles extends Model
{
    use HasFactory;
    public function varti()
    {
        return $this->hasMany(Varti::class, 'speles_id');
    }
    public function komanda()
    {
        return $this->belongsTo(Komanda::class, 'komandas_id');
    }
}
