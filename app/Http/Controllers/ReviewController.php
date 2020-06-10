<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reviews;
use App\Sites;
use App\User;
use Mail;
use App\Mail\EmailNotification;
use Options;

class ReviewController extends Controller
{

    // single item
    public function single( Sites $review ) {

        // check if business is under review and is not admin
        if( !session()->has( 'admin' ) AND $review->publish == 'No' )
            return view( 'company-under-review' );
            

    	// get reviews for this site
    	$reviews = $review->reviews()
                            ->wherePublish( 'Yes' )
                            ->orderByDesc( 'id' )
                            ->paginate(10);

    	// get average rating
    	$averageRating = @number_format($reviews->avg( 'rating' ), 2) ?? 0.00;

        // set seo title 
        $seo_title =  $review->business_name . ' - ' . $review->url . ' ' .  _('Reviews ');

        // get current user auth
        $alreadyReviewed = false;

        if( !auth()->guest() && auth()->user()->id ) {

            $alreadyReviewed = Reviews::where( 'user_id', auth()->user()->id )
                                  ->where( 'review_item_id', $review->id )
                                  ->exists();


        }

    	return view( 'review-single', compact( 'reviews', 'review', 'averageRating', 
                                                'seo_title', 'alreadyReviewed' ) );

    }

    // take review
    public function takeReview( Sites $r, Request $request ) {

        $this->middleware( 'auth' );

        // validate
        $this->validate( $request, [ 'rating' => 'required|integer|between:1,5',
                                     'review_title' => 'required|min:2',
                                     'review_content' => 'required|min:5']);

        // insert review
        $review = new Reviews( $request->only(['rating','review_title', 'review_content']) );
        $review->user_id = auth()->user()->id;

        try{

            // save review
            $id = $r->reviews()->save( $review );

            // set sweet alert message
            alert()->success(_('Your review was sent to review and will soon be published if it abides by our TOS'), _('Thank you'));

            // notify admin by email
            $data[ 'message' ] = sprintf(_('New review to %s
                                  Reviewer: %s
                                  Title: %s
                                  Rating: %s
                                  Review Content: %s'), 

                                '<strong>'.$r->url.'</strong><br>',
                                $review->reviewer_name . '<br>',
                                $review->review_title . '<br>',
                                $review->rating . '<br>',
                                $review->review_content
                                );

            $data[ 'intromessage' ] = _('New Review awaiting approval');
            $data[ 'url' ] = route( 'reviewsForSite', [ 'site' => $r->url ]);
            $data[ 'buttonText' ] = _('See Review');

            Mail::to(Options::get_option( 'adminEmail' ))->send( new EmailNotification( $data ) );


            return back();

        }catch( \Exception $e ) {
            return back()->with( 'message', $e->getMessage() );
        }

    }

    // take reply as company
    public function replyAsCompany( Reviews $r, Request $req ) {
            
        if( !$req->has( 'replyTo_' . $r->id ) ) {
            return back()->with( 'message', _('Please enter the reply content.') );
        }

        // get review item 
        $reviewItem = $r->site->claimedBy;
        
        if( auth()->user()->id != $reviewItem ) {
            return back()->with( 'message', _('You do not seem to own this review item id.') );
        }

        // save reply
        $r->company_reply = $req->{"replyTo_" . $r->id};
        $r->save();

        return back()->with( 'message', _('Your reply was saved.') );

    }


}
