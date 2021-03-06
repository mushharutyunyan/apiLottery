<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;

    const ADMIN = 1;
    const USER = 2;
    public static $role = array(1 => 'Admin', 2 => 'User');
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','email_token','count_requests','api_token','call_email_notification'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function payment()
    {
        return $this->hasMany('App\Models\Payment','user_id');
    }

    public function history()
    {
        return $this->hasMany('App\Models\CallHistory','user_id');
    }
}
