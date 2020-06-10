<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::model('site', App\Sites::class);
Route::model('review', App\Reviews::class);
Route::model('subscription', App\Subscriptions::class);

Route::group(['middleware' => 'App\Http\Middleware\LMiddleware'], function () {

Route::get('/', 'HomeController')->name( 'home' );
Route::get( 'sitemap.xml', 'SitemapController' )->name( 'sitemap' );

// Browse Categories
Route::get('browse-categories', 'CategoryController@browse')->name( 'categories' );
Route::get('browse-category/{slug}', 'CategoryController@browseCategory')->name( 'browse-category' );

// Search Controller
Route::get( 'search', 'SearchController@search' )->name( 'search' );

// Account Redirect
Route::get( 'home', function() { return redirect()->route( 'myaccount' ); });

// Reviews Page for Site
Route::get('reviews/{site}', 'ReviewController@single')->name('reviewsForSite');
Route::post( 'process-new-review/{site}', 'ReviewController@takeReview' )->name( 'takeReview' );
Route::post( 'reviews/replyAsCompany/{review}', 'ReviewController@replyAsCompany' )->name( 'replyAsCompany' );

// Submit Controller
Route::get('submit-company', 'SubmitController@submitCompanyForm' )->name('addCompany');
Route::post('process-new-company', 'SubmitController@submitStore' )->name('storeCompany');

// Companies
Route::get( 'companies-plans', 'CompaniesController@plans' )->name( 'companiesPlans' );
Route::get( 'company-claim/{site}/plan/{plan}', 'CompaniesController@claim' )        
        ->where( 'plan', 'yearly|monthly|6months')
        ->name( 'companyClaim' )
        ->middleware( 'auth' );

Route::post( 'verify-ownership-form/{site}', 'CompaniesController@verifyOwnershipForm' )
        ->name( 'verifyOwnershipForm' )
        ->middleware( 'auth' );

Route::get( 'ownership-verify', 'CompaniesController@ownershipVerify' );

// Checkout
Route::get( 'checkout/select-payment-method', 'CheckoutController@selectPayment' )->name( 'select-payment-method' );
Route::get('checkout/credit-card', 'CheckoutController@credit_card')->name( 'checkout.credit-card' );
Route::post('checkout/credit-card', 'CheckoutController@credit_card_processing');
Route::get('checkout/paypal', 'CheckoutController@paypal')->name( 'checkout.paypal' );
Route::get('checkout/success', 'CheckoutController@success')->name( 'checkout.success' );
Route::post('checkout/paypal-complete', 'CheckoutController@paypal_complete')->name( 'checkout.paypal-complete' );

// Auth Routes
Auth::routes(['verify' => true]);

Route::get( 'my-account', 'AccountController@myReviews' )->name( 'myaccount' );
Route::get( 'my-profile', 'AccountController@myProfile' )->name( 'myprofile' );
Route::put( 'update-profile', 'AccountController@updateProfile' )->name( 'updateprofile' );
Route::get( 'my-shortlist', 'AccountController@shortlist' )->name( 'myshortlist' );
Route::get( 'delete-review/{reviewId}', 'AccountController@deleteReview' )->name( 'deleteReview' );
Route::get( 'update-review/{reviewId}', 'AccountController@updateReview' )->name( 'updateReview' );
Route::post( 'update-my-review/{reviewId}', 'AccountController@updateMyReview' )->name( 'updateMyReview' );
Route::get( 'my-company', 'AccountController@myCompany' )->name( 'mycompany' );
Route::get( 'my-embedded-codes', 'AccountController@myEmbeddedCodes' )->name( 'myEmbeddedCodes' );
Route::post( 'account/setCompanyWidgetColors', 'AccountController@setCompanyWidgetColors' );
Route::get( 'manage-company', 'AccountController@manageCompany' )->name( 'manageCompany' );
Route::post( 'manage-company', 'AccountController@manageCompanyProcess' )->name( 'manageCompanyProcess' );
Route::get( 'my-billing', 'AccountController@myBilling' )->name( 'mybilling' );
Route::get( 'cancel-subscription/{subscription}', 'AccountController@cancelSubscription' )->name( 'subscriptionCancel' );


// Terms of Service Page
Route::get( 'terms-of-service', 'PageController@tos' )->name( 'tos' );
Route::get( 'privacy-policy', 'PageController@privacy' )->name( 'privacy' );

// Contact
Route::get('contact', 'PageController@contact')->name( 'contact' );
Route::post('contact', 'PageController@process_contact');

// Admin log-in
Route::any('admin/login', 'AdminController@login')->name( 'adminLogin' );

// Admin log-out
Route::any('admin/logout', 'AdminController@logout');

// Static Pages
Route::get( 'p-{any}', 'PageController@page' );

// admin panel routes
Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {
    
    Route::get('/', 'AdminController@dashboard');

    // categories
    Route::get( 'categories', 'AdminController@categories' );
    Route::post('update_category', 'AdminController@update_category');
    Route::post( 'add_category', 'AdminController@add_category' );

    // companies
    Route::get( 'companies', 'AdminController@companies' );
    Route::get( 'companies/delete/{site}', 'AdminController@deleteCompany' );
    Route::get( 'companies/approve/{site}', 'AdminController@approveCompany' );
    Route::get( 'companies/edit/{site}', 'AdminController@editCompany' );
    Route::post( 'companies/update/{site}', 'AdminController@updateCompany' );

    // bulk import
    Route::get( 'bulk', 'AdminController@bulk' );
    Route::post( 'bulk-import', 'AdminController@bulkImport' );

    // users
    Route::get( 'users', 'AdminController@users' );
    Route::get( 'users/delete/{user}', 'AdminController@deleteUser' );
    Route::any( 'users/assign-company/{user}', 'AdminController@manuallyAssignCompany' )->name( 'manuallyAssign.company' );

    // reviews
    Route::get( 'reviews', 'AdminController@reviews' );
    Route::get( 'reviews/approve/{review}', 'AdminController@approveReview' );
    Route::get( 'reviews/edit/{review}', 'AdminController@editReview' );
    Route::post( 'reviews/update/{review}', 'AdminController@updateReview' );
    Route::get( 'reviews/delete/{review}', 'AdminController@deleteReview' );

    // CMS 
    Route::get('cms', ['uses' => 'AdminController@pages', 'as' => 'admin-cms'] );
    Route::post('cms', 'AdminController@create_page');
    Route::get('cms-edit/{id}', 'AdminController@editPage');
    Route::post('cms-edit/{id}', 'AdminController@updatePage');
    Route::get('cms-delete/{id}', 'AdminController@deletePage');

    // configuration
    Route::get( 'configuration', 'AdminController@configuration' );
    Route::post( 'configuration', 'AdminController@updateConfiguration' );
    Route::get( 'mailconfiguration', 'AdminController@mailConfiguration' );
    Route::post( 'mailconfiguration', 'AdminController@updateMailConfiguration' );
    Route::get( 'mailtest', 'AdminController@mailtest' );

    // ads
    Route::get( 'ads', 'AdminController@ads' );
    Route::Post( 'ads', 'AdminController@adsProcessing' );


});

});

// Validate product
Route::get( 'validate-license', function(  ) {
    return view( 'validate-license' )->with( 'seo_title', 'Validate your product' );
});

Route::post( 'validate-license', 'PageController@activate_product');
