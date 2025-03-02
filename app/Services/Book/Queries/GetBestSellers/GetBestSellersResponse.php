<?php

declare(strict_types=1);

namespace app\Services\Book\Dto\GetBestSellers;

final readonly class GetBestSellersResponse
{
    /**
     * @param array<string, mixed> $json
     */
    public function __construct(
        public array $json,
    ) {}
}
