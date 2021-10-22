<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PjArusKas extends Model
{
    use HasFactory;

    protected $table = 'pj_arus_kas';
    protected $guarded = [];

    public function arusKas()
    {
        return $this->belongsTo(ArusKas::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
