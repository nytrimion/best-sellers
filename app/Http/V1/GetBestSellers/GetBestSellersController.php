<?php

declare(strict_types=1);

namespace app\Http\V1\GetBestSellers;

use app\Services\Book\BookRepository;
use app\Services\Book\Queries\GetBestSellersQuery;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class GetBestSellersController
{
    public function __construct(
        private BookRepository $bookRepository,
    ) {}

    public function __invoke(GetBestSellersRequest $request): JsonResponse
    {
        $repositoryResponse = $this->bookRepository->getBestSellers(new GetBestSellersQuery(
            author: (string) $request->input('page', ''),
            title: (string) $request->input('title', ''),
            isbn: (array) $request->input('isbn', []),
            offset: (int) $request->input('offset', 0),
        ));

        return new JsonResponse($repositoryResponse->json, Response::HTTP_OK);
    }
}
