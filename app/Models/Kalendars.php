<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalendars extends Model
{
    use HasFactory;
  
    protected $table = 'trenini';

    protected $fillable = [
        'apraksts', 'laiks', 'vieta', 'komandas_id', 'sakuma_datums', 'beigu_datums'
    ];

    public function komanda()
    {
        return $this->belongsTo(Komanda::class, 'komandas_id');
    }
}