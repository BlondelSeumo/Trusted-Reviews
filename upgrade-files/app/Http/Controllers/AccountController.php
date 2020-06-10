<?php

namespace App\Http\Controllers;

use App\Reviews;
use App\Subscriptions;
use App\Sites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Options;

class AccountController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    // customize colors of widget
    public function setCompanyWidgetColors( Request $r ) {

        $this->validate( $r, [ 'field' => 'required', 'value' => 'required' ] );
        
        // get this user company if any
        $company = auth()->user()->company;   

        if( !$company )
            return response()->json([ 'error' => 'No company for your account' ]);

        // validate fields
        $allowedField = [ 'generalBG', 'generalFC', 'testiGB', 'testiFC', 'urlFC' ];

        if(!in_array( $r->field, $allowedField ))
            return response()->json([ 'error' => 'Invalid field name' ]);

        // update this option
        Options::update_option( $r->field . '_' . $company->id, $r->value );

        return response()->json([ 'success' => 'true' ]);

    }

    // my embedded codes
    public function myEmbeddedCodes() {

        // get this user company if any
        $company = auth()->user()->company;   

        if( !$company )
            return view( 'account/no-company' );

        return view( 'account/embedded-codes', compact( 'company' ) );

    }

    // show user reviews
    public function myReviews(  ) {

        // set active nav
        $activeNav = 'account';

        // set seo title
        $seo_title = _('My Reviews') . ' - ' . env( 'APP_NAME' );

        // get this user reviews
        $myreviews = auth()->user()->reviews()->with('site')->get();

        return view( 'account/my-reviews', compact('myreviews', 'seo_title'));
    }

    // delete review
    public function deleteReview( $reviewId ) {
        
        try {

            // find review
            $r = Reviews::findOrFail( $reviewId );

            if( auth()->user()->id != $r->user_id )
                throw new \Exception( __( 'You cannot remove a review that you do not own' ) );

            // delete review
            $r->delete();

            // set message
            alert()->success('Successfully removed review', __('Success'));

            return back();

        } catch( \Exception $e ) {
            alert()->error($e->getMessage(), __( 'OOPS' ));
            return back();
        }

    }

    // update review
    public function updateReview( $reviewId ) {
        
        try {

            // find review
            $r = Reviews::findOrFail( $reviewId );

            if( auth()->user()->id != $r->user_id )
                throw new \Exception( __( 'You cannot update a review that you do not own' ) );

            // set seo title
            $seo_title = __('Update My Review') . ' - ' . env( 'APP_NAME' );

            // update review form
            return view( 'account/update-my-review', compact( 'r','seo_title' ) );


        } catch( \Exception $e ) {
            alert()->error($e->getMessage(), __( 'OOPS' ));
            return back();
        }

    }

    // process review update
    public function updateMyReview( $reviewId, Request $request ) {
        
        try {

            // find review
            $r = Reviews::findOrFail( $reviewId );

            if( auth()->user()->id != $r->user_id )
                throw new \Exception( __( 'Again, you cannot update a review that you do not own' ) );

            // validate
            $this->validate( $request, [ 'rating' => 'required|integer|between:1,5',
                                     'review_title' => 'required|min:2',
                                     'review_content' => 'required|min:5']);
            
            // update review
            $r->review_title = $request->review_title;
            $r->rating = $request->rating;
            $r->review_content = $request->review_content;
            $r->publish = 'No';
            $r->save();

            alert()->success( __( 'Your rating is under review now.'), __('Success' ) );

            // return to my reviews
            return redirect()->route( 'myaccount' );


        } catch( \Exception $e ) {
            alert()->error($e->getMessage(), __( 'OOPS' ));
            return back();
        }

    }

    // show user profile
    public function myProfile() {

        // set active nav
        $activeNav = 'account';

        // set seo title
        $seo_title = __('My Profile') . ' - ' . env( 'APP_NAME' );

        return view( 'account/my-profile', compact( 'activeNav', 'seo_title' ));
    }

    // update user profile
    public function updateProfile( Request $r ) {
        
        $this->validate( $r, [  'name' => 'required', 
                                'email' => 'email|required' ] );

        // is email changed as well?
        if( $r->email !=  auth()->user()->email )
            $this->validate( $r, [ 'email' => 'email|required|unique:users' ] );

        // set user variable
        $user = auth()->user();

        // update user 
        $user->name = $r->name;
        $user->email = $r->email;
        $user->save();

        // also update password
        if( $r->has( 'password' ) AND !empty( $r->password ) ) {
            $user->password =  Hash::make( $r->password );
            $user->save();
        }

        alert()->success(__('Successfully updated your details.'), __( 'Success' ));

        return back();

    }

    // my company
    public function myCompany(  ) {
        
        // get this user company if any
        $company = auth()->user()->company;

        return view( 'account.my-company', compact( 'company' ) );

    }

    // manage company
    public function manageCompany(  ) {
        
        // get this user company if any
        $company = auth()->user()->company;

        if( !$company )
            return route( 'home' );

        // get category list
        $categories = app('rinvex.categories.category')->all();

        return view( 'account.manage-company', compact( 'company', 'categories' ) );

    }

    // manage company processing
    public function manageCompanyProcess( Request $r ) {

        $this->validate( $r, [ 'company_logo' => 'image' ] );
        
        // get this user company if any
        $company = auth()->user()->company;

        if( !$company )
            return route( 'home' );

        // update company details
        $company->business_name = $r->business_name;
        $company->location = $r->location;
        $company->lati = $r->lati;
        $company->longi = $r->longi;
        $company->metadata = [ 'description' => $r->company_description, 
                               'notifications_email' => $r->notifications_email ];

        $company->save();
                         


        // update company category
        $this->__updateCompanyCategory( $company, $r->category_id );


        // do we also have a logo?
        if( $r->hasFile('company_logo') ) {

            // set file info
            $file = $r->file('company_logo');
            $filename = str_slug( $company->url ) . '.' . $file->getClientOriginalExtension();

            // move file
            $file->move( base_path() . '/domain-logos/', $filename ) ;

            // create thumbnail & save
            $img = \Image::make( base_path() . '/domain-logos/' . $filename);
            $img->resize(null, 98, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $img->save( base_path() . '/domain-logos/thumbnail-' . $filename );

            // set logo in db
            $currentMetadata = $company->metadata;

            if( !is_null( $currentMetadata ) ) {
                $currentMetadata[ 'logo'] = $filename;
                $company->metadata = $currentMetadata;
            }else{
                $company->metadata = ['logo' => $filename];
            }
            $company->save();

        }

        alert()->success(__('Successfully updated company details.'), __( 'Success' ));

        return back();

    }

    // my billing
    public function mybilling(  ) {

        // get billing infos
        $subscriptions = auth()->user()->subscriptions;

        return view( 'account.my-billing', compact( 'subscriptions' ) );

    }

    // cancel subscription
    public function cancelSubscription( Subscriptions $s ) {
        
        try {
            // validate ownership of this subscription
            if( auth()->user()->id != $s->user_id )
                throw new \Exception( 'You do not seem to own this subscription id' );

            // update status
            $s->subscription_status = 'Canceled';
            $s->save();

            if( stristr($s->gateway, 'card' ) !== false ) {

                // set stripe secret
                \Stripe\Stripe::setApiKey(Options::get_option('STRIPE_SECRET_KEY'));

                $sub = \Stripe\Subscription::retrieve($s->subscription_id);
                $sub->cancel();

            }

        } catch( \Exception $e ) {
            alert()->error(__($e->getMessage()), __( 'OOPS' ));
        }

        return back();

    }

    // set category
    private function __updateCompanyCategory( Sites $p, int $categoryId ) {
        $p->syncCategories( $categoryId, true);
    }


}
