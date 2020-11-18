<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use App\PasswordResetNotification;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','city','state','country','pincode', 'password','mobile','code','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function store($request)
    {
        $this->fill($request->all());
        $sms = $this->save();
        return response()->json($sms, 200);
    }
    
    public function updateModel($request)
    {
    $this->update($request->all());
    return $this;
    }

    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    public function sendPasswordResetNotification($token){
        $this->notify(new PasswordResetNotification($token));
    }
}
