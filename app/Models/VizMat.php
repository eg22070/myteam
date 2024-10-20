<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VizMat extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo( related:User::class);
    }

    public function speletajs()
    {
        return $this->belongsTo(Speletajs::class);
    }
}
