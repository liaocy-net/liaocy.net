<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // custome direction
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
          
         // admin is just a directive name that i want to create
       // return korbe authentication check korbe login ache kina
       // && auth () jodi login means user hoy ebong er role ID 1 kina 
          
        });
    }
}
