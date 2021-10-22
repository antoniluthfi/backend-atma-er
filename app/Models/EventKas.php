<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventKas extends Model
{
    use HasFactory;

    protected $table = 'event_kas';
    protected $guarded = [];

    public function arusKas()
    {
        return $this->hasMany(ArusKas::class);
    }
}
