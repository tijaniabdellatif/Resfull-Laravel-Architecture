<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    

    protected $dates = ['deleted_at'];


    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verif_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verif_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Mutator setName
     *
     * @param [String] $name
     * @return String name
     */
    public function setNameAttribute($name){
         $this->attributes['name'] = strtolower($name) ;
    }

    public function getNameAttribute($name):String {
        return \ucwords($name);
    }

   public function setEmailAttribute($email){
    $this->attributes['email'] = strtolower($email) ;
    }



    public function isVerified(){

         return $this->verified === User::VERIFIED_USER;
    }


    public function isAdmin(){

        return $this->admin === User::ADMIN_USER;

    }

    public static function generateVerifCode(){

        return Str::random(40);
    }



}
