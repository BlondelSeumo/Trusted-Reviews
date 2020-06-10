<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sites;

class Verify extends Model
{
    // no timestamps
    public $timestamps = false;

    // table
    public $table = 'verify';

    // relationship to site
    public function site() {
    	return $this->belongsTo( Sites::class );
    }

}
