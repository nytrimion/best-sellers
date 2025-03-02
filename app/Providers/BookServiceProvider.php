<?php

namespace App\Providers;

use App\Services\Book\BookRepository;
use App\Services\Book\Repositories\NewYorkTimesBookRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookRepository::class, static function (Application $app): BookRepository {
            $config = $app->get(Repository::class)['services']['book']['newyorktimes'];

            return new NewYorkTimesBookRepository(
                $config['api_url'] ?? '',
                $config['api_key'] ?? '',
                $config['retries'] ?? 3,
                $config['timeout'] ?? 5,
            );
        });
    }
}
