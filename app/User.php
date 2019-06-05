<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const UTILIZADOR_VERIFICADO = '1';
    const UTILIZADOR_NAO_VERIFICADO = '0';

    const UTILIZADOR_ADMIN = 'true';
    const UTILIZADOR_REGULAR = 'false';

    protected $table='users';
    protected $dates=['deleted_at'];
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

    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }
    public function getNameAttribute($valor){
       return ucwords($valor);
    }
    public function setEmailAttribute($valor){
        $this->attributes['email'] = strtolower($valor);
    }
    /*public getEmailAttribute($valor){
       return ucfirst($valor);
    }*/

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