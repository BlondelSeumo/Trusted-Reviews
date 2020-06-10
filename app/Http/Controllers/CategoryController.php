<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sites;
use DB;

class CategoryController extends Controller
{
    // browse categories
    public function browse(  ) {

    	$categories = [  ];
    	$active = 'categories';

    	$categories = app('rinvex.categories.category')->orderBy('slug')->get();
        $seo_title = __( 'Browse Categories' ) . ' - ' . env( 'APP_NAME' );

    	return view( 'browse-categories', compact( 'active', 'categories', 'seo_title' ) );


    }

    // browse a single category
    public function browseCategory( $slug ) {
    	
    	$categories = [  ];
    	$active = 'categories';

    	// get this category
    	$category = app('rinvex.categories.category')->whereSlug( $slug )->firstOrFail();

    	// set seo title
        $seo_title = __( 'Browse' ) . ' ' . $category->name .  ' - ' . env( 'APP_NAME' );

        // get sites in this category
        $sites = $category->entries( \App\Sites::class )->pluck('id');

        // general query
        $sites = Sites::whereIn( 'sites.id', $sites );

		$sites->select( 'sites.id', 'sites.business_name', 'sites.url', 'sites.location', 
		         DB::raw( 'AVG( reviews.rating ) AS rating' ), 
		         DB::raw( 'COUNT( reviews.id ) AS reviewsCount' )  )
		->groupBy( 'sites.id');

        // company status filter
        if( request()->has( 'companyStatus' ) ) {
        	$statusFilter = request('companyStatus');

        	switch ($statusFilter) {
        		case 'all':
        			# do nothing
        			break;

        		case 'claimed';
        			$sites->whereNotNull( 'claimedBy' );
        			break;

        		case 'unclaimed';
        			$sites->whereNull( 'claimedBy' );
        			break;
        		
        	}
        }
		
        // rating sort filter
        if( request()->has( 'sortBy' ) ) {
        	$sortFilter = request('sortBy');

        	switch ($sortFilter) {
        		case 'default':
        			$sites->orderByDesc( 'rating' );
        			$sites->leftJoin( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        			break;

        		case 'best';
        			$sites->orderByDesc( 'rating' );
        			$sites->join( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        			break;

        		case 'worst';
        			$sites->orderBy( 'rating' );
        			$sites->join( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        			break;

        		case 'most-reviews':
        			$sites->orderByDesc( 'reviewsCount' );
        			$sites->join( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        			break;

        		case 'least-reviews':
        			$sites->orderBy( 'reviewsCount' );
        			$sites->leftJoin( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        			break;
        		
        	}
        }else{
        		$sites->orderByDesc( 'rating' );
        		$sites->leftJoin( 'reviews', 'reviews.review_item_id', '=', 'sites.id' );
        }

         // reviews count filter
        if( request()->has( 'reviewsCount' ) ) {
        	$sites->havingRaw( 'COUNT(reviews.id) >= '. request('reviewsCount') );
        }

        // location filter
        $location = null;
        if( request()->filled('lati') AND request()->filled( 'longi' ) AND request()->filled( 'location' ) ) {

            // set location for the view
            $location = request('location');

            $lati = request( 'lati' );
            $longi = request( 'longi' );

            // 25 miles radius location search
            $haversine = "(6371 * acos(cos(radians($lati)) * cos(radians(lati)) * cos(radians(longi) - radians($longi)) + sin(radians($lati)) * sin(radians(lati))))";

            // radius
            $radius = 25;

            // apply query
            $sites->selectRaw("{$haversine} AS distance")->whereRaw("{$haversine} < ?", [$radius]);

        }

        // set reset url
        $getParams = request()->except([ 'location','lati','longi' ]);
        $resetURL = url()->current() . '?' . http_build_query( $getParams );

        $sites = $sites->paginate(10);

    	return view( 'browse-category', compact( 'active', 'category', 'seo_title', 
                                                'resetURL', 'sites', 'location' ) );

    }

    // 

}
