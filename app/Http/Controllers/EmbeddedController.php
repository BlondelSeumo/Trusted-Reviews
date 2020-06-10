<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sites;

class EmbeddedController extends Controller
{
    // render embedded iframe
    public function embeddedIframe( Sites $c ) {
        
        // get this company reviews
        $reviews = $c->reviews;

        $content = view( 'iframe', compact( 'c', 'reviews' ) );

        return response( $content )
        				->header( 'Access-Control-Allow-Methods', 'GET,OPTIONS' )
        				->header( 'Access-Control-Allow-Origin', '*' );

    }


}
