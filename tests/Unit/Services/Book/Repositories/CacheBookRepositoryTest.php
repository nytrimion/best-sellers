<?php

declare(strict_types=1);

namespace tests\Unit\Services\Book\Repositories;

use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryException;
use App\Services\Book\Queries\GetBestSellersQuery;
use App\Services\Book\Queries\GetBestSellersResponse;
use App\Services\Book\Repositories\CacheBookRepository;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class CacheBookRepositoryTest extends TestCase
{
    private const int CACHE_LIFETIME_SEC = 10;

    private CacheBookRepository $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new CacheBookRepository(
            \Mockery::mock(BookRepository::class),
            self::CACHE_LIFETIME_SEC,
        );
    }

    public function testGetBestSellersReturnsCachedResponseWithNoGivenParams(): void
    {
        $response = new GetBestSellersResponse(['foo' => 'bar']);

        Cache::spy()
            ->shouldReceive('remember')
            ->once()
            ->with(
                \Mockery::capture($cacheKey),
                self::CACHE_LIFETIME_SEC,
                \Mockery::type('callable'),
            )
            ->andReturn($response);

        $this->assertSame($response, $this->sut->getBestSellers(new GetBestSellersQuery()));
        $this->assertSame('book/best-sellers', $cacheKey);
    }

    public function testGetBestSellersReturnsCachedResponseWithAllGivenParams(): void
    {
        $response = new GetBestSellersResponse(['foo' => 'bar']);

        Cache::spy()
            ->shouldReceive('remember')
            ->once()
            ->with(
                \Mockery::capture($cacheKey),
                self::CACHE_LIFETIME_SEC,
                \Mockery::type('callable'),
            )
            ->andReturn($response);

        $this->assertSame($response, $this->sut->getBestSellers(new GetBestSellersQuery(
            author: 'John',
            title: 'Whatever',
            isbn: ['9780553293388', '0553293389'],
            offset: 40,
        )));
        $this->assertSame('book/best-sellers?author=John&isbn%5B0%5D=0553293389&isbn%5B1%5D=9780553293388&offset=40&title=Whatever', $cacheKey);
    }

    public function testGetBestSellersThrowsBookRepositoryExceptionWhenCacheHasFailed(): void
    {
        $exception = new \Exception('unexpected error');

        Cache::spy()
            ->shouldReceive('remember')
            ->andThrow($exception);

        $this->expectException(BookRepositoryException::class);
        $this->expectExceptionMessage('book/best-sellers?author=John&isbn%5B0%5D=0553293389&isbn%5B1%5D=9780553293388&offset=40&title=Whatever');

        $this->sut->getBestSellers(new GetBestSellersQuery(
            author: 'John',
            title: 'Whatever',
            isbn: ['9780553293388', '0553293389'],
            offset: 40,
        ));
    }
}
