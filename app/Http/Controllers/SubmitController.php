<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Sites;
use App\Mail\EmailNotification;
use App\User;

use Mail;
use Options;


class SubmitController extends Controller
{

    // construct
    public function __construct() {
        $this->middleware(['auth', 'verified']);
        
    }


    // submit company form
    public function submitCompanyForm(  ) {
        
        $categories = app('rinvex.categories.category')->all();
        $seo_title = __( 'Submit Company' ) . ' - ' . env( 'APP_NAME' );

        return view('submit-company', compact( 'categories', 'seo_title' ));

    }


    // process new entry
    public function submitStore ( Request $r )
    {

        $this->validate( $r, [ 'url' => 'required|url' ]);

        // does this url exist?
        if( !urlExists( $r->url ) ) {
            alert()->error(__('This URL could not be reached. Please check for errors'), __('URL Error'));
            return back();
        }

        // remove scheme from url
        $uri = str_ireplace([ 'http://', 'https://' ], ['', ''], $r->url);
        $uri = rtrim( $uri, '/' );

        // check for duplicates
        if( Sites::whereUrl( $uri )->exists() ) {
            alert()->error(__('We already have this company listed'), __('Already Exists'));
            return back();
        }

        // save this site
        $site = new Sites;
        $site->url = $uri;
        $site->business_name = $r->name;
        $site->lati = $r->lati;
        $site->longi = $r->longi;
        $site->location = $r->city_region;
        $site->submittedBy = auth()->user()->id;
        $site->save();

        // attach category to this site
        $this->__updateCategory( $site, $r->category_id );

        // notify admin by email
        $data[ 'message' ] = sprintf(_('New business added on the website called %s
                              Location: %s
                              Site URL: %s'), 
                                '<strong>'.$r->name.'</strong><br>', 
                                '' . $r->city_region . '<br>', 
                                '<a href="'.$r->url.'">' . $uri . '</a>'
                                );

        $data[ 'intromessage' ] = _('New business added');
        $data[ 'url' ] = route( 'reviewsForSite', [ 'site' => $site->url ]);
        $data[ 'buttonText' ] = _('See Listing');

        Mail::to(Options::get_option( 'adminEmail' ))->send( new EmailNotification( $data ) );

        // set success message
        alert()->success(__('This company has been added and will be reviewed before publishing to our site.'), __('Company Added'));

        // redirect to the new listing
        return redirect()->route( 'home' );


    }

    // set category
    private function __updateCategory( Sites $p, int $categoryId ): object {
        return $p->syncCategories( $categoryId, true);
    }

}
