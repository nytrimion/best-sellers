<?php

declare(strict_types=1);

namespace tests\Unit\Services\Book\Repositories;

use app\Services\Book\BookRepositoryException;
use app\Services\Book\Queries\GetBestSellersQuery;
use app\Services\Book\Repositories\NewYorkTimesBookRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class NewYorkTimesBookRepositoryTest extends TestCase
{
    private NewYorkTimesBookRepository $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new NewYorkTimesBookRepository('https://api.example.com', '12345');
    }

    public function testGetBestSellersReturnsResponseWhenNewYorkTimesApiResponseIsSuccessful(): void
    {
        Http::fake([
            '*' => Http::response(['foo' => 'bar'], 200),
        ]);
        $response = $this->sut->getBestSellers(new GetBestSellersQuery());
        [$request] = Http::recorded()[0];

        $this->assertSame('https://api.example.com/books/v3/lists/best-sellers/history.json?api-key=12345', $request->url());
        $this->assertSame(['foo' => 'bar'], $response->json);
    }

    public function testGetBestSellersReturnsResponseWithAllQueryParameters(): void
    {
        Http::fake([
            '*' => Http::response([], 200),
        ]);
        $this->sut->getBestSellers(new GetBestSellersQuery(
            author: 'John',
            title: 'Whatever',
            isbn: ['0553293389', '9780553293388'],
            offset: 40,
        ));
        [$request] = Http::recorded()[0];

        $this->assertStringEndsWith('?author=John&title=Whatever&isbn=0553293389%3B9780553293388&offset=40&api-key=12345', $request->url());
    }

    public function testGetBestSellersThrowsBookRepositoryExceptionWhenHttpClientConnectionHasFailed(): void
    {
        Http::fake([
            '*' => Http::failedConnection(),
        ]);
        $this->expectException(BookRepositoryException::class);
        $this->expectExceptionMessage('https://api.example.com/books/v3/lists/best-sellers/history.json?api-key=12345');

        $this->sut->getBestSellers(new GetBestSellersQuery());
    }

    public function testGetBestSellersThrowsBookRepositoryExceptionWhenNewYorkTimesApiResponseHasFailed(): void
    {
        Http::fake([
            '*' => Http::response('Too Many Requests', 429),
        ]);
        $this->expectException(BookRepositoryException::class);
        $this->expectExceptionMessage('Too Many Requests');
        $this->expectExceptionCode(429);

        $this->sut->getBestSellers(new GetBestSellersQuery());
    }

    public function testGetBestSellersThrowsBookRepositoryExceptionWhenNewYorkTimesApiResponseIsScalar(): void
    {
        Http::fake([
            '*' => Http::response('"string"', 200),
        ]);
        $this->expectException(BookRepositoryException::class);
        $this->expectExceptionMessage("string");

        $this->sut->getBestSellers(new GetBestSellersQuery());
    }
}
