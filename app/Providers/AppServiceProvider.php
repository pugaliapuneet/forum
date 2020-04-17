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
        \View::composer(['thread.create', 'layouts.app'], function($view) {
            $view->with('channels', \App\Channel::all());
        });
        // \View::share('channels', \App\Channel::all());
    }
}
