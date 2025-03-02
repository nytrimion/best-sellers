<?php

namespace App\Providers;

use App\Http\V1\GetBestSellers\GetBestSellersController as GetBestSellersControllerV1;
use App\Http\V2\GetBestSellers\GetBestSellersController as GetBestSellersControllerV2;
use App\Services\Book\BookRepository;
use App\Services\Book\Repositories\CacheBookRepository;
use App\Services\Book\Repositories\NewYorkTimesBookRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app
            ->when(GetBestSellersControllerV1::class)
            ->needs(BookRepository::class)
            ->give(static fn(Application $app): BookRepository => $app->get(NewYorkTimesBookRepository::class));

        $this->app
            ->when(GetBestSellersControllerV2::class)
            ->needs(BookRepository::class)
            ->give(static fn(Application $app): BookRepository => $app->get(CacheBookRepository::class));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
