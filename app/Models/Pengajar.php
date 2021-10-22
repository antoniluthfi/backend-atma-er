<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajar extends Model
{
    use HasFactory;

    protected $table = 'pengajar';
    protected $guarded = [];

    public function eventPengajian()
    {
        return $this->belongsTo(EventPengajian::class);
    }
}
