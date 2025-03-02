<?php

declare(strict_types=1);

namespace App\Services\Book\Queries;

final readonly class GetBestSellersResponse
{
    /**
     * @param array<string, mixed> $json
     */
    public function __construct(
        public array $json,
    ) {}
}
