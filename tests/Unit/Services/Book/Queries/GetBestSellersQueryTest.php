<?php

declare(strict_types=1);

namespace tests\Unit\Services\Book\Queries;

use App\Services\Book\Queries\GetBestSellersQuery;
use Tests\TestCase;

final class GetBestSellersQueryTest extends TestCase
{
    public function testItTrimsAuthor(): void
    {
        $sut = new GetBestSellersQuery(author: '  whatever  ');

        $this->assertSame('whatever', $sut->author);
    }

    public function testItTrimsTitle(): void
    {
        $sut = new GetBestSellersQuery(title: '  whatever  ');

        $this->assertSame('whatever', $sut->title);
    }

    public function testItRemovesIsbnDuplicates(): void
    {
        $sut = new GetBestSellersQuery(isbn: [
            'foo',
            'bar',
            'foo',
            'baz',
            'bar',
        ]);
        $this->assertSame([
            'foo',
            'bar',
            'baz',
        ], $sut->isbn);
    }
}
