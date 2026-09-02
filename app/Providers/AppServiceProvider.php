<?php

namespace App\Providers;

use App\Interfaces\BookRepositoryInterface;
use App\Interfaces\BorrowingRepositoryInterface;
use App\Repositories\BookRepository;
use App\Repositories\BorrowingRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            BookRepositoryInterface::class,
            BookRepository::class
        );

        $this->app->bind(
            BorrowingRepositoryInterface::class,
            BorrowingRepository::class
        );
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
