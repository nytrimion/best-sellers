<?php

declare(strict_types=1);

namespace tests\Unit\Services\Book;

use app\Services\Book\BookRepositoryException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BookRepositoryExceptionTest extends TestCase
{
    public function testHasFailedToFetchResource(): void
    {
        $previous = new \Exception();
        $sut = BookRepositoryException::hasFailedToFetchResource('whatever', 42, $previous);

        $this->assertSame('Book Repository has failed to fetch the resource: whatever', $sut->getMessage());
        $this->assertSame(42, $sut->getCode());
        $this->assertSame($previous, $sut->getPrevious());
    }

    /**
     * @return iterable<string, array{
     *     content: mixed,
     *     expected: string,
     * }>
     */
    static public function hasFetchedUnexpectedContentProvider(): iterable
    {
        yield 'null type' => [
            'content' => null,
            'expected' => 'null',
        ];
        yield 'bool type' => [
            'content' => true,
            'expected' => 'true',
        ];
        yield 'integer type' => [
            'content' => 42,
            'expected' => '42',
        ];
        yield 'float type' => [
            'content' => 12.34,
            'expected' => '12.34',
        ];
        yield 'string type' => [
            'content' => 'whatever',
            'expected' => '"whatever"',
        ];
        yield 'object type' => [
            'content' => (object) ['foo' => 'bar'],
            'expected' => '{"foo":"bar"}',
        ];
    }

    #[DataProvider('hasFetchedUnexpectedContentProvider')]
    public function testHasFetchedUnexpectedContent(mixed $content, string $expected): void
    {
        $previous = new \Exception();
        $sut = BookRepositoryException::hasFetchedUnexpectedContent($content, 42, $previous);

        $this->assertSame("Book Repository has fetched unexpected content: $expected", $sut->getMessage());
        $this->assertSame(42, $sut->getCode());
        $this->assertSame($previous, $sut->getPrevious());
    }
}
