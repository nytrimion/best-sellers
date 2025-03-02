<?php

declare(strict_types=1);

namespace app\Services\Book;

use app\Services\Book\Dto\GetBestSellers\GetBestSellersQuery;
use app\Services\Book\Dto\GetBestSellers\GetBestSellersResponse;

interface BookRepository
{
    /**
     * @throws BookRepositoryException
     */
    public function getBestSellers(GetBestSellersQuery $query): GetBestSellersResponse;
}
