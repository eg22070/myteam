<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pazinojums extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'virsraksts',
        'pazinojums',
        'datums',
        'owner_id', // Ensure 'owner_id' is also fillable if you're using create()
    ];
    
    public function user()
    {
        return $this->belongsTo( User::class, 'owner_id');
    }
    
    public function komanda()
    {
    return $this->belongsTo(Komanda::class);
    }
}
