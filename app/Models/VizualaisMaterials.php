<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VizualaisMaterials extends Model
{
    use HasFactory;

    protected $table = 'vizualais_materials';

    protected $fillable = [
        'coach_id',
        'komandas_id',
        'virsraksts',
        'komentars',
        'bilde',      
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function komanda()
    {
        return $this->belongsTo(Komanda::class, 'komandas_id');
    }
}
