<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPengajian extends Model
{
    use HasFactory;

    protected $table = 'event_pengajian';
    protected $guarded = [];

    public function kehadiran()
    {
        return $this->belongsTo(Kehadiran::class);
    }

    public function rekapPengajian()
    {
        return $this->hasMany(RekapPengajian::class);
    }

    public function pengajar()
    {
        return $this->hasMany(Pengajar::class);
    }
}
