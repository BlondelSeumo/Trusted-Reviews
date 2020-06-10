<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{

    public $fillable = [ 'review_item_id', 'user_id', 'rating', 
                        'review_title', 'review_content', 'publish' ];

    // relationship to sites
    public function site()
    {
        return $this->belongsTo(Sites::class, 'review_item_id', 'id');
    }


    // relationship to user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // get time ago attribute
    public function getTimeAgoAttribute() {
        Carbon::setLocale(env('LOCALE'));
        return Carbon::parse( $this->created_at )->diffForHumans();
    }

    // get reviewer name
    public function getReviewerAttribute() {
    	return @$this->user->name;
    }
    
}
