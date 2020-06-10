<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Sites;

class Subscriptions extends Model
{
    // no timestamps please
    public $timestamps = false;

    // belongs to user
    public function user(  ) {
    	return $this->belongsTo( User::class );
    }

    // has one site
    public function site(  ) {
    	return $this->hasOne( Sites::class, 'id', 'site_id' );
    }


}
