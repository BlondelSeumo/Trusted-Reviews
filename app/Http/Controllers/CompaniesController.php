<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sites;
use App\Mail\EmailNotification;
use App\Verify;

use Mail;
use Hash;
use Options;

class CompaniesController extends Controller
{
    // plans
    public function plans(  ) {

    	$seo_title = _( 'For Companies - Plans' );
    	return view( 'companies-plans', compact('seo_title') );
    }

    // claim
    public function claim( Sites $company, String $plan ) {

        if( !is_null($company->claimedBy )) {
            alert()->error( _( 'This company is already claimed' ), _( 'OOPS' ) );
            return redirect(route( 'home' ));
        }
    	
    	// verify ownership
    	return view( 'verify-ownership', compact( 'company', 'plan' ) );


    }

    // verify ownership
    public function verifyOwnershipForm( Sites $c, Request $r ) {
	
		// validate    	
    	$this->validate( $r, [ 'username' => 'required', 'plan' => 'in:6months,yearly,monthly' ] );


    	// create hash
    	$code = Hash::make(md5(rand( 1,99999999 )));

    	// create entry hash
    	$verify = new Verify;
    	$verify->plan = $r->plan;
    	$verify->site_id = $c->id;
    	$verify->hash = $code;
    	$verify->save();

    	// send verification email
        $data[ 'message' ] = sprintf(_('Hey there,%s 
                                      someone requested an ownership verification of your company %s on our platform.
                                      Follow the link to verify ownership'), '<br>', '<strong>'.$c->business_name.'</strong>' );

        $data[ 'intromessage' ] = _( 'Company Ownership Verification' );
        $data[ 'url' ] = env( 'APP_URL' ) . '/ownership-verify?code=' . $code;

        $data[ 'buttonText' ] = _('Validate Ownership');

        Mail::to( auth()->user()->email )->send( new EmailNotification( $data ) );

        return view( 'verify-ownership-message' );


    }

    // ownership verification
    public function ownershipVerify( Request $r ) {
    	
    	// check site by code
    	if( !$r->has( 'code' ) )
    		return redirect( '/' );

    	// check hash exists
    	$verify = Verify::where( 'hash', $r->code )->first();

    	if( !$verify ) {
			alert()->warning(_( 'Invalid verification code.'), _( 'OOPS' ));
    		return redirect( '/' );
    	}

    	// all good now
    	$verify->verified = 'yes';
    	$verify->save();

    	// redirect to payment plan page
    	session([ 'ownershipVerified' => $r->code ]);

    	// set plan 
    	session(['ownershipPlan' => $verify->plan]);

    	// set site
    	session(['ownershipSite' => $verify->site]);

    	// alert
    	alert()->success( _( sprintf('You have successfully validated ownership of %s', $verify->site->url )), 
    	                 _( 'Hurray' ) );


    	return redirect(route( 'select-payment-method' ));

    }
}
