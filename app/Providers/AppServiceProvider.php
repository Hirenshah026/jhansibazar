<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        //
        DB::statement("SET time_zone = '+05:30'");
        View::composer('*', function ($view) {
            $setting = DB::table('settings')->get();
            $view->with('setting', $setting);
        });
    }
}
