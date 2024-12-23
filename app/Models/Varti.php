<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varti extends Model
{
    use HasFactory;
    public function speles()
    {
        return $this->belongsTo(Speles::class);
    }
}
