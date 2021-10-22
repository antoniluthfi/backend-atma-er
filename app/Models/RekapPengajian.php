<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPengajian extends Model
{
    use HasFactory;

    protected $table = 'rekap_pengajian';
    protected $guarded = [];

    public function eventPengajian()
    {
        return $this->belongsTo(EventPengajian::class);
    }
}
