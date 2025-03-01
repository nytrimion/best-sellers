<?php

declare(strict_types=1);

namespace App\Http\GetBestSellers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class GetBestSellersController
{
    public function __invoke(GetBestSellersRequest $request): JsonResponse
    {
        $payload = ['foo' => 'bar'];

        return new JsonResponse($payload, Response::HTTP_OK);
    }
}
