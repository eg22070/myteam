<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speletajs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        
        'komanda_id',
        'numurs',
        'dzimsanas_datums',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function komanda()
    {
    return $this->belongsTo(Komanda::class);
    }
}
