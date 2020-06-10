<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // user reviews
    public function reviews(  )
    {
        return $this->hasMany(Reviews::class);
    }

    // user company
    public function company(  ) {
        return $this->hasOne( Sites::class, 'claimedBy', 'id' );
    }

    // subscriptions
    public function subscriptions() {
        return $this->hasMany( Subscriptions::class );
    }


}
