<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trenins extends Model
{
    use HasFactory;

    protected $fillable = ['apraksts', 'laiks', 'vieta', 'komandas_id']; // Add team_id to fillable

    public function trenini()
    {
        return $this->belongsTo(Komandas::class);
    }
}
