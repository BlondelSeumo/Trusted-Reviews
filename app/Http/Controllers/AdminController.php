<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Sites;
use App\User;
use App\Mail\EmailNotification;
use App\Reviews;
use App\Page;
use App\Subscriptions;

use Mail;
use Options;
use Artisan;


class AdminController extends Controller
{

   // GET|POST /admin/login
    public function login() {

        $message = '';

        if( \Request::isMethod('post') ) {
            if( \Input::has('ausername') AND \Input::has('apassword') ) {
                if( \Input::get('ausername') == env('ADMIN_USER') AND 
                    \Input::get('apassword') == env('ADMIN_PASS') ) {
                    \Session::put('admin', 'true');
                    return redirect('admin');
                }else{
                    $message = '<div class="alert alert-danger">Invalid login.</div>';  
                }
            }else{
                $message = '<div class="alert alert-danger">User/password required</div>';
            }
        }

        return view('admin-login')->with('message', $message);
    }

    // GET /admin/logout
    public function logout() {
        \Session::forget('admin');
        return redirect('/admin/login');
    }

    public function dashboard() {
        
        $figures = [  ];

        // get total # of companies
        $companies = Sites::count();
        $figures[ 'companies' ] = $companies;

        // get total # of reviews
        $reviews = Reviews::count();
        $figures[ 'reviews' ] = $reviews;

        // earnings past 30 days
        $date = strtotime( '31 days ago' );

        $days = Subscriptions::select(array(
                \DB::raw('FROM_UNIXTIME(`subscription_date`, "%Y-%d-%m" ) as `date`'),
                \DB::raw('SUM(`subscription_price`) as `total`')
            ))
            ->where('subscription_date', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->pluck('date','total');

        // monthly value
        $monthlyPlans = Subscriptions::wherePlan( 'monthly' )
                                        ->where( 'subscription_status', 'Active' )
                                        ->sum('id');
        $halfYearPlans = Subscriptions::wherePlan( '6months' )
                                        ->where( 'subscription_status', 'Active' )
                                        ->sum('id');
        $yearlyPlans = Subscriptions::wherePlan( 'yearly' )
                                        ->where( 'subscription_status', 'Active' )
                                        ->sum('id');
        // 

        return view( 'admin/dashboard', compact( 'figures', 'days', 'monthlyPlans', 
                                                    'halfYearPlans', 'yearlyPlans' ) );

    }

    public function categories(  ) {
        
        // if remove
        if( $removeId = \Input::get('remove') ) {
            
            // remove from db
            $d = app('rinvex.categories.category')->find( $removeId );
            $d->delete();

            return redirect('admin/categories')->with('msg', 'Successfully removed category "'.$d->name.'"');
        }


        // if update
        $catname = '';
        $catID = '';
        if( $updateCat = \Input::get( 'update' ) ) {

            // find category
            $c = app('rinvex.categories.category')->find( $updateCat );
            $catname = $c->name;
            $catID = $c->id;

        }

        $categories = app('rinvex.categories.category')->orderBy('id', 'ASC')->get();
        $active = 'categories';

        return view( 'admin/categories', compact( 'categories', 'catname', 'catID', 'active' ) );

    }

    // add category
    public function add_category( Request $r  ) {

        $this->validate( $r, [ 'catname' => 'required' ] );

        $c = app('rinvex.categories.category')->create([ 'name' => ['en' => $r->catname] ]);

        return redirect( 'admin/categories' )->with( 'msg', 'Category successfully created.' );
        
    }

    // update category
    public function update_category( Request $r  ) {

        $this->validate( $r, [ 'catname' => 'required' ] );

        $c = app('rinvex.categories.category')->find( $r->catID );
        $c->name = $r->catname;
        $c->save();

        return redirect( 'admin/categories' )->with( 'msg', 'Category successfully updated.' );
        
    }

    // set category
    private function __updateCompanyCategory( Sites $p, int $categoryId ): object {
        return $p->syncCategories( $categoryId, true);
    }


    // companies
    public function companies(  ) {
        
        // get companies
        $pending_companies = Sites::wherePublish('no')->orderBy('id')->get();
        $companies = Sites::wherePublish('yes')->orderByDesc('id')->get();
        $category = app('rinvex.categories.category');

        $active = 'companies';

        return view( 'admin/companies', compact( 'pending_companies', 'companies', 'active' ));

    }

    // delete company
    public function deleteCompany( Sites $company ) {
        
        // delete this company reviews
        $company->reviews()->delete();

        // delete this company
        $company->delete();

        return redirect( 'admin/companies' )->with( 'msg', 'Company successfully removed.' );

    }

    // approve company
    public function approveCompany( Sites $company ) {
        
        // approve this company
        $company->publish = 'yes';
        $company->save();

        // email the submitter 
        $data[ 'message' ] = sprintf('The business you suggested %s
                              Location: %s
                              Site URL: %s
                              %s Good news, it was approved and is now live on our website.
                              %s You can find it at %s', 

                                '<strong>'.$company->business_name.'</strong><br>', 
                                $company->location . '<br>',
                                '<a href="https://'.$company->url.'">' . $company->url . '</a>',
                                '<br><hr><br>',
                                '<br>', 
                                route( 'reviewsForSite', ['site' =>$company->url] )
                                );

        $data[ 'intromessage' ] = _('New business approved');
        $data[ 'url' ] = route( 'reviewsForSite', [ 'site' => $company->url ]);
        $data[ 'buttonText' ] = _('See Listing');

        $submitterEmail = $company->submitter->email;
        Mail::to($submitterEmail)->send( new EmailNotification( $data ) );

        return redirect( 'admin/companies' )->with( 'msg', 'Company successfully approved.' );

    }

    // edit company
    public function editCompany( Sites $company ) {
            
        $categories = app('rinvex.categories.category')->all();
        $active = 'companies';

        return view( 'admin/edit-company', compact( 'company', 'categories', 'active' ) );

    }

    // update company
    public function updateCompany( Sites $company, Request $r ) {
        
        $company->url = $r->url;
        $company->business_name = $r->name;
        $company->location  = $r->city_region;
        $company->lati = $r->lati;
        $company->longi = $r->longi;
        $company->save();

        $this->__updateCompanyCategory( $company, $r->category_id );

        return redirect( 'admin/companies' )->with( 'msg', 'Company successfully updated.' );

    }


    // users overview
    public function users(  ) {
        
        $users = User::orderByDesc( 'id' )->get();
        $active = 'users';

        return view( 'admin/users', compact( 'users','active' ) );

    }

    // delete user
    public function deleteUser( User $user ) {
        
        // delete user reviews
        $user->reviews()->delete();

        // delete user company
        $user->company()->delete();

        // delete user account
        $user->delete();

        // redirect
        return redirect( 'admin/users' )->with( 'msg', 'User and all data associated successfully removed.' );

    }

    // manually assign company to user
    public function manuallyAssignCompany( User $user ) {

        if( request()->has( 'companyID' ) ) {

            if( is_null(request()->companyID) ) {
                
                // remove claimed by
                $currentUserCompany = $user->company;

                if( $currentUserCompany ) {

                    $site = Sites::find( $currentUserCompany->id );
                    $site->claimedBy = null;
                    $site->save();
                }


            }else{

                // remove claimed by
                $sites = Sites::where( 'claimedBy', $user->id )->get();

                foreach( $sites as $site ) {
                    $site->claimedBy = null;
                    $site->save();
                }

                
                // set claimed by
                $site = Sites::find( request()->companyID );
                $site->claimedBy = $user->id;
                $site->save();

                // set subscription
                // $s = new Subscriptions;
                // $s->plan = 'Manual';
                // $s->site_id = $site->id;
                // $s->subscription_id = 'Manual';
                // $s->gateway = 'Admin';
                // $s->subscription_date = time();
                // $s->subscription_status = 'Active';
                // $s->subscription_price = 0;
                // $s->save();

            }

            return back()->with( 'msg', 'Assignment successfully saved');

        }
        
        // get unassigned companies
        $companies = Sites::whereNull( 'claimedBy' )->orderBy('business_name')->get();

        return view( 'admin/manually-assign-company', compact( 'user', 'companies' ) );

    }

    // reviews overview
    public function reviews(  ) {
        
        // get reviews
        $pending_reviews = Reviews::wherePublish('no')->orderBy('id')->get();
        $reviews = Reviews::wherePublish('yes')->orderByDesc('id')->get();

        $active = 'reviews';

        return view( 'admin/reviews', compact( 'pending_reviews', 'reviews', 'active' ));

    }

    // approve review
    public function approveReview( Reviews $review ) {
        
        // approve this review
        $review->publish = 'yes';
        $review->save();

        // email the submitter 
        $data[ 'message' ] = sprintf('Hi there, 
                            Good news, your review was approved and is now live on our website.
                            %s You can find it at %s', 

                            '<br>', route( 'reviewsForSite', ['site' =>$review->site->url] ));

        $data[ 'intromessage' ] = 'Your Review Was Approved';
        $data[ 'url' ] = route( 'reviewsForSite', [ 'site' => $review->site->url ]);
        $data[ 'buttonText' ] = 'See Review';

        $submitterEmail = $review->user->email;
        Mail::to($submitterEmail)->send( new EmailNotification( $data ) );

        // email the owner ( if any )
        if( !is_null($review->site->claimedBy) AND !is_null( $review->site->metadata ) ) {
            if( isset( $review->site->metadata[ 'notifications_email' ] ) ) {

                // is this user subscription active?
                $userID = $review->site->claimedBy;

                $isSubscriptionActive = Subscriptions::where( 'site_id', $review->site->id )
                            ->where( 'user_id', $userID )
                            ->where( 'subscription_status', 'Active' )
                            ->exists();

                if( $isSubscriptionActive ) {

                    $data[ 'message' ] = sprintf('Hi there, 
                                  Your company has received a review and is now live on our website.
                                  %s You can find it at %s', 

                                '<br>',
                                route( 'reviewsForSite', ['site' =>$review->site->url] ));

                    $data[ 'intromessage' ] = _('Your Company New Review');
                    $data[ 'url' ] = route( 'reviewsForSite', [ 'site' => $review->site->url ]);
                    $data[ 'buttonText' ] = _('See Review');

                    $claimerEmail = $review->site->metadata[ 'notifications_email' ];
                    Mail::to($claimerEmail)->send( new EmailNotification( $data ) );

                }

            }
        }

        return redirect( 'admin/reviews' )->with( 'msg', 'Review successfully approved.' );

    }

    // edit review
    public function editReview( Reviews $r ) {
        
        $active = 'reviews';

        return view( 'admin/edit-review', compact( 'r', 'active' ) );

    }

    // update review
    public function updateReview( Reviews $r, Request $req ) {

        // validate
        $this->validate( $req, [ 'rating' => 'required|integer|between:1,5',
                                     'review_title' => 'required|min:2',
                                     'review_content' => 'required|min:5']);
        
        $r->rating = $req->rating;
        $r->review_title = $req->review_title;
        $r->review_content = $req->review_content;
        $r->save();

        return redirect( 'admin/reviews' )->with( 'msg', 'Review successfully updated.' );

    }

    // delete review
    public function deleteReview( Reviews $r ) {
        
        // delete this company reviews
        $r->delete();

        return redirect( 'admin/reviews' )->with( 'msg', 'Review successfully removed.' );

    }

    // pages controller
    public function pages() {
        
        // get existent pages
        $pages = Page::all();

        return view('admin.pages')->with('pages', $pages)
                                  ->with('active', 'pages');
    }

    // create a page
    public function create_page( Request $r ) {
        
        // validate form entries
        $this->validate( $r, ['page_title' => 'unique:pages|required']);

        // save page
        $page = new Page;
        $page->page_title = $r->page_title;
        $page->page_slug  = str_slug( $r->page_title );
        $page->page_content = $r->page_content;
        $page->save();

        return redirect()->route('admin-cms')->with('msg', 'Page successfully created');

    }

    // edit page
    public function editPage( $id ) {

        $page = Page::findOrFail($id);
        return view('admin.update-page')->with('p', $page)->with('active', 'pages');

    }

    // update page
    public function updatePage( $id, Request $r ) {
        
        $page = Page::findOrFail($id);
        $page->page_title = $r->page_title;
        $page->page_content = $r->page_content;
        $page->save();

        return redirect('/admin/cms-edit/' . $id)->with('msg', 'Page successfully created');

    }

    // delete page
    public function deletePage( $id ) {
        
        if( $id != 1 ) {
            Page::destroy($id);
            $msg = 'Page successfully removed';
        } else {
            $msg = 'You cannot remove homepage sorry.';
        }


        return redirect()->route('admin-cms')->with('msg', $msg);

    }

    // configuration
    public function configuration(  ) {
        
        return view('admin.configuration')->with('active', 'config');
        
    }

    // ads
    public function ads(  ) {
        return view( 'admin.ads' )->with( 'active', 'ads' );
    }

    // ads processing
    public function adsProcessing( Request $r ) {
        
        $options = request()->except('_token', 'sb_settings');

        // save options
        foreach( $options as $name => $value ) {
            Options::update_option( $name, $value );
        }

        return redirect('admin/ads')->with('msg', 'Ads successfully saved!');

    }

    // update configuration
    public function updateConfiguration( ) {
        
        $options = request()->except('_token', 'sb_settings');

        // save options
        foreach( $options as $name => $value ) {
            Options::update_option( $name, $value );
        }

        // homepage image updated?
        $headImage = '';
        if( request()->hasFile('homepage_header_image') ) {
            $ext = request()->file('homepage_header_image')->getClientOriginalExtension();
            $destinationPath = public_path();
            $fileName = uniqid(rand()) . '.' . $ext;
            request()->file('homepage_header_image')->move($destinationPath, $fileName);
            $headImage = Options::update_option('homepage_header_image', $fileName);
        }

        // logo image updated?
        $logoImage = '';
        if( request()->hasFile('logo_image') ) {
            $ext = request()->file('logo_image')->getClientOriginalExtension();
            $destinationPath = public_path();
            $fileName = uniqid(rand()) . '.' . $ext;
            request()->file('logo_image')->move($destinationPath, $fileName);
            $logoImage = Options::update_option('site.logo', $fileName);

            // dd( $logoImage );
        }

        return redirect('admin/configuration')->with('msg', 'Configuration settings successfully saved!');

    }

    // mail configuration
    public function mailconfiguration(  ) {
        
        return view( 'admin/mail-configuration', [ 'active' => 'mailconfig' ] );

    }

     // update mail configuration
    public function updateMailConfiguration( Request $r ) {
        
        $i = $r->except(['sb_settings', '_token']);
        
        foreach( $i as $k => $v ) {
            $this->__setEnvironmentValue( $k, $v );
        }


        return redirect('admin/mailconfiguration')->with('msg', 'Mail Configuration settings successfully saved!');

    }

    // mail test
    public function mailtest(  ) {
        

        $data[ 'message' ] = 'This is a test email to check your mail server configuration.';

        $data[ 'intromessage' ] = 'Mail Server Configuration';
        $data[ 'url' ] = env( 'APP_URL' ) . '/admin/mailconfiguration';
        $data[ 'buttonText' ] = 'See Mail Configuration';

        $adminEmail = Options::get_option('adminEmail');

        try {
            $result = Mail::to($adminEmail)->send( new EmailNotification( $data ) );
            return redirect('admin/mailconfiguration')->with('msg', 'Mail sent to your server, it is up to them to deliver it now.');
        } catch( \Exception $e ) {
             return redirect('admin/mailconfiguration')->with('msg', $e->getMessage());
        }


    }

    // bulk form
    public function bulk(  ) {
        return view( 'admin/bulk-import', [ 'active' => 'bulk' ] );
    }

    // bulk import
    public function bulkImport( Request $r ) {
        
        $this->validate( $r, ['csv_file' => 'required' ]);

        $csv = $r->file( 'csv_file' );

       $handle   = fopen( $csv, 'r' );

       $row = 0;
       $skipped = 0;

        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

            // remove scheme from url
            $uri = str_ireplace([ 'http://', 'https://' ], ['', ''], $data[0]);
            $uri = rtrim( $uri, '/' );

            // check for duplicates
            if( Sites::whereUrl( $uri )->exists() ) {
                echo $uri . ' exists<br>';
                $skipped++;
                continue;
            }

            // increase rows
            $row++;

            // setup fields
            $site = new Sites;
            $site->url = $uri;
            $site->business_name = $data[1];
            $site->lati = $data[2];
            $site->longi = $data[3];
            $site->location = $data[4];
            $site->publish = 'yes';
            $site->save();

            $categoryName = $data[5];
            $categorySlug = Str::slug($categoryName);
            $categoryID = \DB::table( 'categories' )->select('id')->whereSlug( $categorySlug )->first();

            if( $categoryID ) {
                $categoryID = $categoryID->id;
            }else{

                $c = app('rinvex.categories.category')->create(['name' => ['en' =>  $categoryName], 'slug' => $categorySlug]);

                $categoryID = $c->id;

            }

            // append category
            $this->__updateCompanyCategory( $site, $categoryID );


        }// the csv lines loop

        // close handle to the temp file
        fclose($handle);

        return back()->with( 'msg', 'Inserted ' . $row . ' companies. ' . $skipped . ' were duplicates.' );


    }

    private function __setEnvironmentValue($envKey, $envValue)
    {

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str = preg_replace('/'.$envKey.'=([^\n]*)/is', $envKey.'='.$envValue, $str);

        $fp = fopen($envFile, 'w');
        $didWrite =  fwrite($fp, $str);
        fclose($fp);

        return $didWrite;


    }


}
