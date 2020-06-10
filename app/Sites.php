<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{

    use \Rinvex\Categories\Traits\Categorizable;
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'url';
    }

    // cast to array
    public $casts = [ 'metadata' => 'array' ];

    // relationship to reviews
    public function reviews()
    {
        return $this->hasMany( Reviews::class, 'review_item_id', 'id' );
    }

    // relationship to claimer
    public function claimer()
    {
        return $this->belongsTo( User::class, 'claimedBy', 'id');
    }

    // relationship to submitter
    public function submitter()
    {
        return $this->belongsTo( User::class, 'submittedBy', 'id');
    }


    // compute slug
    public function getSlugAttribute() {
    	return route('reviewsForSite', ['url' => strtolower($this->url)]);
    }

    // get screenshot
    public function getScreenshotAttribute (  )
    {
        if( isset( $this->metadata ) && isset( $this->metadata[ 'logo' ] ) )
            return asset('domain-logos/' . $this->metadata[ 'logo' ]);
        else
            return 'http://free.pagepeeker.com/v2/thumbs.php?size=m&url=http://' . $this->url;
    }


}
