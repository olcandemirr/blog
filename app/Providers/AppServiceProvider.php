<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // HTML sanitize için helper fonksiyon
        if (!function_exists('clean')) {
            function clean($html)
            {
                return \Purifier::clean($html);
            }
        }
    }
}
