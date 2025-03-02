<?php

declare(strict_types=1);

namespace App\Services\Book\Repositories;

use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryException;
use App\Services\Book\Queries\GetBestSellersQuery;
use App\Services\Book\Queries\GetBestSellersResponse;
use Illuminate\Support\Facades\Cache;

final readonly class CacheBookRepository implements BookRepository
{
    private const string BEST_SELLERS_KEY = 'book/best-sellers';
    private const int DEFAULT_LIFETIME_SEC = 300;

    public function __construct(
        private BookRepository $decorated,
        private int $lifetime = self::DEFAULT_LIFETIME_SEC,
    ) {}

    public function getBestSellers(GetBestSellersQuery $query): GetBestSellersResponse
    {
        $params = (array) $query;

        sort($params['isbn']);

        $cacheKey = $this->canonicalizeCacheKey(self::BEST_SELLERS_KEY, $params);

        try {
            return Cache::remember(
                $cacheKey,
                $this->lifetime,
                fn(): GetBestSellersResponse => $this->decorated->getBestSellers($query),
            );
        } catch (\Throwable $throwable) {
            throw BookRepositoryException::hasFailedToFetchResource(
                description: $cacheKey,
                previous: $throwable,
            );
        }
    }

    /**
     * @param array<string, mixed> $params
     */
    private function canonicalizeCacheKey(string $prefix, array $params): string
    {
        $key = rtrim($prefix, '?');
        $params = array_filter($params, static fn(mixed $value): bool => !empty($value));

        if ($params !== []) {
            ksort($params);
            $key .= '?' . http_build_query($params);
        }

        return $key;
    }
}
