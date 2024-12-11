<?php

namespace App\Providers;

use App\QueryBuilder\ProcessQueryBuilder;
use App\Services\ProcessService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProcessQueryBuilder::class, function ($app) {
            return new ProcessQueryBuilder();
        });

        $this->app->singleton(ProcessService::class, function ($app) {
            return new ProcessService($app->make(ProcessQueryBuilder::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
    }
}
