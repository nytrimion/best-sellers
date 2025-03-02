<?php

declare(strict_types=1);

namespace App\Services\Book;

final class BookRepositoryException extends \DomainException
{
    public static function hasFailedToFetchResource(string $description, int $code = 0, ?\Throwable $previous = null): self
    {
        return new BookRepositoryException(
            'Book Repository has failed to fetch the resource: ' . $description,
            $code,
            $previous,
        );
    }

    public static function hasFetchedUnexpectedContent(mixed $content, int $code = 0, ?\Throwable $previous = null): self
    {
        return new BookRepositoryException(
            'Book Repository has fetched unexpected content: ' . json_encode($content),
            $code,
            $previous,
        );
    }
}
