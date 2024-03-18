<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        // parent::boot();

        // Carbon::serializeUsing(function ($carbon) {
        //     return $carbon->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        // });
    }

}
