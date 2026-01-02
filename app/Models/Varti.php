<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varti extends Model
{
    use HasFactory;
    protected $table = 'varti';
    protected $fillable = [
        'speles_id',          // match ID
        'vartuGuveja_id',     // goal scorer player ID
        'assist_id',          // assist player ID (nullable)
        'dzeltena_id',        // yellow card player ID (nullable)
        'sarkana_id',         // red card player ID (nullable)
        'minute',             // minute of event
    ];
    public function speles()
    {
        return $this->belongsTo(Speles::class, 'speles_id');
    }

    public function vartuGuvejs()
    {
        return $this->belongsTo(User::class, 'vartuGuveja_id');
    }

    public function assist()
    {
        return $this->belongsTo(User::class, 'assist_id');
    }

    public function dzeltena()
    {
        return $this->belongsTo(User::class, 'dzeltena_id');
    }

    public function sarkana()
    {
        return $this->belongsTo(User::class, 'sarkana_id');
    }

}
