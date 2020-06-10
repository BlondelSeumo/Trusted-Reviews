<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use App\FormFields\BSDateTimeFormField;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // remove mysql "too many characters" issue
        \Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
