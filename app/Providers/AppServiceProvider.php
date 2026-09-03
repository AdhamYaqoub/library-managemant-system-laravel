<?php

namespace App\Providers;

use App\Interfaces\BookRepositoryInterface;
use App\Interfaces\BorrowingRepositoryInterface;
use App\Repositories\BookRepository;
use App\Repositories\BorrowingRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
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
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url('/api/reset-password')
                . '?token=' . $token
                . '&email=' . urlencode($user->email);
        });
    }
}