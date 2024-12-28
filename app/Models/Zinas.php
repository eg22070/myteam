<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zinas extends Model
{
    use HasFactory;

    public function sutitajs()
    {
        return $this->belongsTo(User::class, 'sutitaja_id');
    }

    public function sanemejs()
    {
        return $this->belongsTo(User::class, 'sanemeja_id');
    }
}
