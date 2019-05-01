<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\SingerObserver;
use App\Observers\SongObserver;
use App\Singer;
use App\Song;

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
        Song::observe(SongObserver::class);
    }
}
