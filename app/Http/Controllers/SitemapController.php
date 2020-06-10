<?php

namespace App\Http\Controllers;

use App\Sites;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use View, Response;

class SitemapController extends Controller
{
    // homepage
    public function __invoke()
    {

    	// get category list
    	$categories = app('rinvex.categories.category')->orderBy('slug')->get();

    	// get review items list
    	$companies = Sites::wherePublish( 'yes' )->get();

    	// render view
    	$content = View::make('sitemap', [ 'categories' => $categories, 'companies' => $companies ]);

    	// return xml sitemap
    	return Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
        
    }
}