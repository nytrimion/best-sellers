<?php

declare(strict_types=1);

namespace App\Services\Book;

use App\Services\Book\Queries\GetBestSellersQuery;
use App\Services\Book\Queries\GetBestSellersResponse;

interface BookRepository
{
    /**
     * @throws BookRepositoryException
     */
    public function getBestSellers(GetBestSellersQuery $query): GetBestSellersResponse;
}
