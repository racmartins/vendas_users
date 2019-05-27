<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const UTILIZADOR_VERIFICADO = '1';
    const UTILIZADOR_NAO_VERIFICADO = '0';

    const UTILIZADOR_ADMIN = 'true';
    const UTILIZADOR_REGULAR = 'false';

    protected $table='users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function utilizador_Verificado(){
        return $this->verified == User::UTILIZADOR_VERIFICADO;
    }
    public function utilizador_Administrador(){
        return $this->admin == User::UTILIZADOR_ADMIN;
    }
    public static function gerarVerificationToken(){
        return str_random(40);
    }
}
