<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varti extends Model
{
    use HasFactory;

    protected $table = 'varti';
    public function speles()
    {
        return $this->belongsTo(Speles::class);
    }
    public function vartuGuvejs()
    {
        return $this->belongsTo(Speletajs::class, 'vartuGuveja_id');
    }

    public function assist()
    {
        return $this->belongsTo(Speletajs::class, 'assist_id');
    }
}
