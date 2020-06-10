<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Promoted;
use App\Subscriptions;
use App\Sites;
use App\Mail\EmailNotification;
use Paypal;
use Options;
use Mail;
use App\User;

class CheckoutController extends Controller
{

	private $_apiContext;

	public function __construct() {

		// $payPal = new Paypal;

		// $this->_apiContext = $payPal::ApiContext(Options::get_option('PAYPAL_CLIENT_ID'), Options::get_option('PAYPAL_CLIENT_SECRET'));

  //       $this->_apiContext->setConfig(array(
  //           'mode' => 'live',
  //           'service.EndPoint' => 'https://api.paypal.com',
  //           'http.ConnectionTimeOut' => 30,
  //           'log.LogEnabled' => true,
  //           'log.FileName' => storage_path('logs/paypal.log'),
  //           'log.LogLevel' => 'FINE'
  //       ));
	}

	// select payment method
	public function selectPayment(  ) {

		$this->middleware( 'auth' );

		// validate session variables
		if( !session()->has( 'ownershipVerified' ) || !session()->has( 'ownershipPlan' ) 
		   || !session()->has( 'ownershipSite' ) ) {

			alert()->error( _( 'Invalid session data'), _( 'OOPS' ) );
			return redirect( '/' );

		}

		$plan = session('ownershipPlan');
		$amount = Options::get_option('currency_symbol');

		// get plan price
		$price = $this->__computePrice($plan);

		$amount .= $price;


		return view( 'checkout/select-payment-method', compact( 'plan', 'amount') );

	}

	// compute price dependant on duration
	private function __computePrice( $duration ) {
		
		if( $duration == 'yearly' )
			$price = Options::get_option( 'yearlyPrice' );
		elseif( $duration == 'monthly' )
			$price = Options::get_option( 'monthlyPrice' );
		elseif( $duration == '6months' ) 
			$price = Options::get_option( '6monthsPrice' );
		else
			throw new \Exception( 'Invalid duration: ' . @$duration );
		
		return $price;

	}

    // stripe credit card payment
    public function credit_card() {

    	$this->middleware( 'auth' );

		if( !session()->has( 'ownershipVerified' ) 
		   OR !session()->has( 'ownershipSite' ) 
			OR !session()->has( 'ownershipPlan' ) ) {
			throw new \Exception("Error. No session data validating ownership.");
		}

		// get session
		$site = session( 'ownershipSite' );
		$plan = session( 'ownershipPlan' );

		// compute price
		$price = $this->__computePrice( $plan );
    	
    	return view('checkout.credit-card', [ 'price' => $price, 'plan' => $plan ]);

    }

    // process credit card payment
    public function credit_card_processing( Request $r ) {

    	$this->middleware( 'auth' );

    	$this->validate( $r, [ 'stripeToken' => 'required', 
    	                		'last4' => 'required', 
    	                		'expDate' => 'required' ]  );

    	// set stripe secret
		\Stripe\Stripe::setApiKey(Options::get_option('STRIPE_SECRET_KEY'));

		// do we have plans created?
		$this->__createStripePlans();

		// Get the credit card details submitted by the form
		$token = $r->stripeToken;

		// get session data
		$site = session( 'ownershipSite' );
		$plan = session( 'ownershipPlan' );
		$price = $this->__computePrice($plan);

		if( $plan == 'monthly' )
			$stripePlan = 'ReviewsMonthly';
		elseif( $plan == '6months' )
			$stripePlan = 'ReviewsSixMonths';
		elseif( $plan == 'yearly' )
			$stripePlan = 'ReviewsYearly';

		else
			die( 'Invalid plan:' . strip_tags($plan) );

		// Create the charge on Stripe's servers - this will charge the user's card
		try {

			// Create Stripe Customer
			$customer = \Stripe\Customer::create([
			  "description" => "Ownership for " . $site->url,
			  "source" => $token
			]);

			// Create Subscription
			$subscription = \Stripe\Subscription::create([
			  "customer" => $customer->id,
			  "items" => [
			    [
			      "plan" => $stripePlan,
			    ],
			  ]
			]);


			// get subscription id
			$subscriptionID = $subscription->id;

			// update this company with owner of current authenticated user
			$site->claimedBy = auth()->user()->id;
			$site->save();

			// append card info
			$s[ 'metadata' ]['last4'] = $r->last4;
			$s[ 'metadata' ][ 'expiry' ] = $r->expDate;
			$s[ 'metadata' ][ 'token' ] = $token;

			// save this order in database
			$subPlan = new Subscriptions;
			$subPlan->plan = $plan;
			$subPlan->site_id = $site->id;
			$subPlan->user_id = auth()->user()->id;
			$subPlan->subscription_id = "PP";
			$subPlan->gateway = 'Credit Card (ending in ' . $r->last4 . ')';
			$subPlan->subscription_date = time();
			$subPlan->subscription_status = 'Active';
			$subPlan->subscription_price = $price;
			$subPlan->save();

			$user = auth()->user();

			// mail the user
		    $data[ 'message' ] = _( sprintf('Hello, 
		                           	<br>You have just subscribed and claimed ownership of %s (%s)
		                           	<br>Thanks for joining and enjoy the advantages!', $site->url, Options::get_option('currency_symbol') . $price .'/'. $plan ));

	        $data[ 'intromessage' ] = _('Your Plan Confirmation!');
	        $data[ 'url' ] = route( 'myaccount' );
	        $data[ 'buttonText' ] = _('My Account');

	        Mail::to($user->email)->send( new EmailNotification( $data ) );

		    // mail the admin
		  	$data[ 'message' ] = _( sprintf('Hello, 
		                           	<br>You have just got a new subscriber which claimed ownership of %s (%s)
		                           	<br>Go to admin panel for details', $site->url, Options::get_option('currency_symbol') . $price .'/'. $plan ));

	        $data[ 'intromessage' ] = _('New Subscriber Notification!');
	        $data[ 'url' ] = route( 'adminLogin' );
	        $data[ 'buttonText' ] = _('Admin Panel');

	        Mail::to(Options::get_option( 'adminEmail' ))->send( new EmailNotification( $data ) );

		  	// redirect with success message ( checkout/success )
		  	return redirect('checkout/success');

		} catch(\Exception $e) {
		  	return redirect('checkout/credit-card')
		  				->withErrors([ $e->getMessage() ])
		  				->withInput();
		}
    }

    // create stripe plans
    private function __createStripePlans() {
    	
    	try {
	    	// set stripe secret
			\Stripe\Stripe::setApiKey(Options::get_option('STRIPE_SECRET_KEY'));

			// get the list of plans
			$stripePlans = \Stripe\Plan::all(["limit" => 100]);

			$hasMonthly = false;
			$hasYearly = false;
			$hasSixMonths = false;

			// check if plans exists
			foreach( $stripePlans as $plan ) {

				if( $plan->id == 'ReviewsMonthly' )
					$hasMonthly = true;

				if( $plan->id == 'ReviewsSixMonths' )
					$hasSixMonths = true;

				if( $plan->id == 'ReviewsYearly' )
					$hasYearly = true;

			}

			// if monthly doesn't exist, create it
			if( !$hasMonthly ) {

				$price = $this->__computePrice( 'monthly' );

				\Stripe\Plan::create([
					  "amount" => $price*100,
					  "interval" => "month",
					  "product" => [
					    "name" => "Monthly Company Profile Ownership"
					  ],
					  "currency" => Options::get_option('currency_code'),
					  "id" => "ReviewsMonthly"
					]);

			}

			// if 6 months plan doesn't exist, create it
			if( !$hasSixMonths ) {

				$price = $this->__computePrice( '6months' );

				\Stripe\Plan::create([
					  "amount" => $price*100,
					  "interval" => "month",
					  "interval_count" => 6,
					  "product" => [
					    "name" => "6 Months Company Profile Ownership"
					  ],
					  "currency" => Options::get_option('currency_code'),
					  "id" => "ReviewsSixMonths", 
					]);

			}

			// if yearly doesn't exist, create it
			if( !$hasYearly ) {

				$price = $this->__computePrice( 'yearly' );

				\Stripe\Plan::create([
					  "amount" => $price*100,
					  "interval" => "year",
					  "product" => [
					    "name" => "Yearly Company Profile Ownership"
					  ],
					  "currency" => Options::get_option('currency_code'),
					  "id" => "ReviewsYearly"
					]);

			}


			return \Stripe\Plan::all(["limit" => 100]);

		} catch( \Exception $e ) {
			die( $e->getMessage() );
		}

    }


    // success route
    public function success() {
    	return view('checkout.success');
    }

    // paypal route
    public function paypal() {

    	$this->middleware( 'auth' );

    	// get session data
	    $site = session( 'ownershipSite' );
		$plan = session( 'ownershipPlan' );

	    // compute price paid
		$price = $this->__computePrice( $plan );

		// get user id
		$user = auth()->user()->id;

		// compute paypal compatible duration
		$duration = 1;
		$duration_type = 'M';

		if( 'yearly' == $plan ) {
			$duration = 1;
			$duration_type = 'Y';
		}

		if( '6months' == $plan ) {
			$duration = 6;
		}

    	return view( 'paypal-subscribe', compact( 'site', 'plan', 'price', 'user', 
    	                                         'duration', 'duration_type' ) );

    }

    public function paypal_complete(Request $request) {

    	// error_log( 'PAYPAL_PAYMENT_INITIAED' );

    	$this->validate( $request, [ 'custom' => 'required'] );

    	// STEP 1: read POST data
		// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
		// Instead, read raw POST data from the input stream. 
		$raw_post_data = file_get_contents('php://input');

		// error_log( $raw_post_data );

		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
		     $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
		        $value = urlencode(stripslashes($value)); 
		   } else {
		        $value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}
		 
		// STEP 2: POST IPN data back to PayPal to validate
		$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		// In wamp-like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set 
		// the directory path of the certificate as shown below:
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
		    error_log("Got " . curl_error($ch) . " when processing IPN data");
		    curl_close($ch);
		    exit;
		}else{
			error_log( 'IPN_POSTED_SUCCESSFULLY_CHECKOUTController_LINE_391' );
		}
		curl_close($ch);

		// error_log( 'PayPal RESULT: ' . $res );
		 
		// STEP 3: Inspect IPN validation result and act accordingly
		if (strcmp ($res, "VERIFIED") == 0) {

			if( $_POST[ 'txn_type' ] != 'subscr_signup' ) {
				error_log( 'SUBSCR_TYPE IS NOT subscr_signup BUT ' . $_POST[ 'txn_type' ] );
				exit;
			}
			
		    // check that receiver_email is your Primary PayPal email
		    $receiver_email = $_POST['receiver_email'];
		    if( Options::get_option( 'paypal_email', 'crivion@gmail.com' ) != $receiver_email )  {
		    	error_log( 'RECEIVER_EMAIL = ' . $receiver_email );
		    	error_log( 'SHOULD_BE = ' . Options::get_option( 'paypal_email' ) );
		    	exit;
		    }

		    // get custom data
		    $custom = trim(strip_tags($_POST[ 'custom' ]));
		    list( $plan, $siteId, $userId ) = explode( "_", $custom );

		    // error_log( 'PLAN:' . $plan );
		    // error_log( 'SITE_ID:' . $siteId );
		    // error_log( 'USER_ID:' . $userId );

		    // find this site
		    $site = Sites::findOrFail( $siteId );

		    // error_log( 'FOUND SITE_ID' );

		    // find this user
		    $user = User::findOrFail( $userId );

		    // error_log( 'FOUND USER_ID' );

		    // setup price
		    $price = $this->__computePrice($plan);

		    // error_log( 'FOUND PRICE' );

		    // save this order in database
		    $subPlan = new Subscriptions;
			$subPlan->plan = $plan;
			$subPlan->site_id = $site->id;
			$subPlan->user_id = $user->id;
			$subPlan->subscription_id = $_POST[ 'subscr_id' ];
			$subPlan->gateway = 'PayPal';
			$subPlan->subscription_date = time();
			$subPlan->subscription_status = 'Active';
			$subPlan->subscription_price = $price;
			$subPlan->save();

			// update this company with owner of current authenticated user
			$site->claimedBy = $user->id;
			$site->save();

			// error_log( var_export( $subPlan ) );


			// mail the user
		    $data[ 'message' ] = _( sprintf('Hello, 
		                           	<br>You have just subscribed and claimed ownership of %s (%s)
		                           	<br>Thanks for joining and enjoy the advantages!', $site->url, Options::get_option('currency_symbol') . $price .'/'. $plan ));

	        $data[ 'intromessage' ] = _('Your Plan Confirmation!');
	        $data[ 'url' ] = route( 'myaccount' );
	        $data[ 'buttonText' ] = _('My Account');

	        Mail::to($user->email)->send( new EmailNotification( $data ) );

		    // mail the admin
		  	$data[ 'message' ] = _( sprintf('Hello, 
		                           	<br>You have just got a new subscriber which claimed ownership of %s (%s)
		                           	<br>Go to admin panel for details', $site->url, Options::get_option('currency_symbol') . $price .'/'. $plan ));

	        $data[ 'intromessage' ] = _('New Subscriber Notification!');
	        $data[ 'url' ] = route( 'adminLogin' );
	        $data[ 'buttonText' ] = _('Admin Panel');

	        Mail::to(Options::get_option( 'adminEmail' ))->send( new EmailNotification( $data ) );

	        // error_log( 'CONFIRMED' );

	        $log = '';
			foreach( $_POST as $K => $V ) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}

			// error_log( $log );

			}else{

				$log = '';
				foreach( $_POST as $K => $V ) {
					$log .= $K . '=>' . $V . PHP_EOL;
				}

				error_log( 'Got Invalid Result for TXN_TYPE: ' . $_POST[ 'txn_type' ] );
				error_log( $log );
			}
    	
    }

}