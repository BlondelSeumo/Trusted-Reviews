<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Page;
use Mail;
use Options;
use App\Mail\EmailNotification;

class PageController extends Controller
{
    // render page by slug
    public function page( $slug ) {
        
        // try find this slug
        $page = Page::where( 'page_slug', $slug )->firstOrFail();

        // return page
        return view( 'page' )->with( 'content', $page->page_content )
                             ->with( 'title', $page->page_title );

    }

    public function activate_product( Request $r ) {

        $this->validate( $r, [ 'license' => 'required', 'domain' => 'required|url' ]);

        $result = $this->check_license( $r->license, $r->domain );

        //if LICENSE_VALID_AUTOUPDATE_ENABLED
        if( $result == 'LICENSE_VALID_AUTOUPDATE_ENABLED' )  {

            Options::update_option( 'license_key', $r->license );
        
            return redirect( 'validate-license' )->with( 'message', 'Successfully validated. You can now continue using the product!' );

        }
        else {
            return redirect('validate-license')->with('message', $result);
        }
    
    }

    protected function check_license( $license, $domain ) {

        // call url for licensing
        $url = 'http://crivion.com/envato-licensing/index.php';

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, 2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, 'product=PHP+Trusted+Reviews&license_code=' . $license . '&blogURL=' . $domain);
        curl_setopt($ch,CURLOPT_USERAGENT, 'crivion/envato-license-checker-v1.0');

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $result;

    }

    // Contact page route
    public function contact() {

        $no1   = rand( 1, 5 );
        $no2   = rand( 1, 5 );
        $total = $no1 + $no2;

        return view('contact')
                ->with( 'no1', $no1 )
                ->with( 'no2', $no2 )
                ->with( 'total', $total );;
    }

    // Process contact form entry
    public function process_contact( Request $r ) {

        // validate contact form
        $validator = \Validator::make( $r->all(), [
            'name' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:10',
            'offer-answer' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect('contact')
                        ->withErrors($validator)
                        ->withInput();
        }


         // notify admin by email
        $data[ 'message' ] = sprintf(_('New contact form filled on the website<br>
                                        Name: %s <br>
                                        Subject: %s <br>
                                        Email: %s <br>
                                        Message: %s'), $r->name, $r->subject, $r->email, $r->message );

        $data[ 'intromessage' ] = _('New contact form');
        $data[ 'url' ] = route( 'home' );
        $data[ 'buttonText' ] = _('Go to site');

        Mail::to(Options::get_option( 'adminEmail' ))->send( new EmailNotification( $data ) );

        // set success message
        alert()->success(__('Thanks for getting in touch, we will get back to you as soon as possible.'), __('Message Sent'));

        // redirect to the new listing
        return redirect()->route( 'home' );
        
    }

}