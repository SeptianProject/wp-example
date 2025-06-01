<?php

namespace App\Providers;

use App\Models\Kriteria;
use App\Observers\KriteriaObserver;
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
        Kriteria::observe(KriteriaObserver::class);
    }
}
