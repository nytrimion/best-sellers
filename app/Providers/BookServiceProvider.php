<?php

namespace App\Providers;

use App\Services\Book\Repositories\CacheBookRepository;
use App\Services\Book\Repositories\NewYorkTimesBookRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    public function provides(): array
    {
        return [
            CacheBookRepository::class,
            NewYorkTimesBookRepository::class,
        ];
    }

    public function register(): void
    {
        $this->app->singleton(NewYorkTimesBookRepository::class, static function (Application $app): NewYorkTimesBookRepository {
            $config = $app->get(Repository::class)['services']['book']['newyorktimes'];

            return new NewYorkTimesBookRepository(
                $config['api_url'] ?? '',
                $config['api_key'] ?? '',
                $config['retries'] ?? 3,
                $config['timeout'] ?? 5,
            );
        });
        $this->app->singleton(CacheBookRepository::class, static function (Application $app): CacheBookRepository {
            $config = $app->get(Repository::class)['services']['book']['cache'];

            return new CacheBookRepository(
                $app->get(NewYorkTimesBookRepository::class),
                $config['lifetime'] ?? 300,
            );
        });
    }
}
