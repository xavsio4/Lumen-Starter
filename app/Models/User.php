<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword;
    
    
    const ADMIN_ROLE = 'ADMIN_USER';
    const BASIC_ROLE = 'BASIC_USER';
    
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'users';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'uid',
    'firstName',
    'lastName',
    'middleName',
    'email',
    'password',
    'address',
    'zipCode',
    'username',
    'city',
    'state',
    'country',
    'phone',
    'mobile',
    'role',
    'isActive',
    'profileImage'
    ];
    
    /**
    * The attributes excluded from the model's JSON form.
    *
    * @var array
    */
    protected $hidden = [
    'password', 'remember_token'
    ];
    
    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function bookmark()
    {
        return $this->hasMany('App\Models\Bookmark','user_id');
    }
    
    /**
    * @return bool
    */
    public function isAdmin()
    {
        return (isset($this->role) ? $this->role : self::BASIC_ROLE) == self::ADMIN_ROLE;
    }
}