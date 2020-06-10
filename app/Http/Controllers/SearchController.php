<?php

namespace App\Http\Controllers;

use App\Sites;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // process search form
    public function search( Request $r ) {

    	$this->validate( $r, [ 'searchQuery' => 'required|min:2']);

    	// find sites based on query
        $sites = Sites::wherePublish( 'Yes' )
        			->where( function( $q ) use ( $r ) {
	        			$q->where('url', 'LIKE', '%' . $r->searchQuery . '%')
	                       ->orWhere('business_name', 'LIKE', '%' . $r->searchQuery . '%');
                	})->simplePaginate('20');

        return view('search/results', compact('sites'));

    }

}
