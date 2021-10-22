<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArusKas extends Model
{
    use HasFactory;

    protected $table = 'arus_kas';
    protected $guarded = [];

    public function eventKas()
    {
        return $this->belongsTo(EventKas::class);
    }

    public function pjArusKas()
    {
        return $this->hasMany(PjArusKas::class)->with('users');
    }

    public function users()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email');
    }
}
