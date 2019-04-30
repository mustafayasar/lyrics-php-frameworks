<?php

namespace App\Providers;

use App\Observers\SingerObserver;
use App\Singer;
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
        Singer::observe(SingerObserver::class);
    }
}
