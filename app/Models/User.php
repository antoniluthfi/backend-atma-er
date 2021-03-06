<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'alamat', 'nomorhp', 'nama_ayah', 'nama_ibu', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'status', 'status_akun', 'foto_profil', 'hak_akses', 'fcm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function OauthAccessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function arusKas()
    {
        return $this->hasMany(ArusKas::class);
    }

    public function pjArusKas()
    {
        return $this->hasMany(PjArusKas::class);
    }

    public function userGroup()
    {
        return $this->hasMany(UserGroup::class)->with('group');
    }
}
