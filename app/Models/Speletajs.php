<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speletajs extends Model
{
    use HasFactory;

    public function komanda()
    {
    return $this->belongsTo(Komanda::class);
    }
}
