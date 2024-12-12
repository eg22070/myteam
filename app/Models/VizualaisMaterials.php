<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VizualaisMaterials extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo( related:User::class);
    }

    public function komanda()
    {
        return $this->belongsTo(Komanda::class);
    }
}
