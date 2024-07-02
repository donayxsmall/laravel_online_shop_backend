<?php

namespace App\Providers;


use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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

        // form reusable
        Blade::component('components.forms.text', 'inputText');

        // parent::boot();

        // Carbon::serializeUsing(function ($carbon) {
        //     return $carbon->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        // });
    }

}
