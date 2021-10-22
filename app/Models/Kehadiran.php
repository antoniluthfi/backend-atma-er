<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventKehadiran()
    {
        return $this->hasOne(EventPengajian::class);
    }
}
