<?php

namespace App\Providers;

use App\Clients\BooksClient\BooksClientInterface;
use App\Clients\BooksClient\OpenLibraryClient\OpenLibraryClient;
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
        $this->app->bind(BooksClientInterface::class, fn () => new OpenLibraryClient);
    }
}
