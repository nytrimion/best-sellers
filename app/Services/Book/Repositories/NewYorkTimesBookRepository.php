<?php

declare(strict_types=1);

namespace app\Services\Book\Repositories;

use app\Services\Book\BookRepository;
use app\Services\Book\BookRepositoryException;
use app\Services\Book\Queries\GetBestSellersQuery;
use app\Services\Book\Queries\GetBestSellersResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class NewYorkTimesBookRepository implements BookRepository
{
    private const string BEST_SELLERS_ENDPOINT = '/books/v3/lists/best-sellers/history.json';
    private const string API_KEY_PARAM = 'api-key';
    private const int DEFAULT_RETRIES = 3;
    private const int DEFAULT_RETRY_DELAY_MS = 100;
    private const int DEFAULT_TIMEOUT = 5;

    public function __construct(
        private string $apiUrl,
        private string $apiKey,
        private int $retries = self::DEFAULT_RETRIES,
        private int $timeout = self::DEFAULT_TIMEOUT,
    ) {}

    public function getBestSellers(GetBestSellersQuery $query): GetBestSellersResponse
    {
        $response = $this->get(self::BEST_SELLERS_ENDPOINT, [
            'author' => trim($query->author),
            'title' => trim($query->title),
            'isbn' => implode(';', array_unique($query->isbn)),
            'offset' => $query->offset,
        ]);
        $json = $response->json();

        if (!is_array($json)) {
            throw BookRepositoryException::hasFetchedUnexpectedContent($json);
        }

        return new GetBestSellersResponse($json);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @throws BookRepositoryException
     */
    private function get(string $endpoint, array $params): Response
    {
        $url = $this->getEndpointUrl($endpoint);
        $params = $this->completeAndCleanGetParams($params);

        try {
            return Http::retry($this->retries, self::DEFAULT_RETRY_DELAY_MS)
                ->timeout($this->timeout)
                ->get($url, $params);
        } catch (ConnectionException $exception) {
            throw BookRepositoryException::hasFailedToFetchResource(
                description: $params === [] ? $url : $url . '?' . http_build_query($params),
                previous: $exception,
            );
        } catch (RequestException $exception) {
            throw BookRepositoryException::hasFailedToFetchResource(
                description: $exception->getMessage(),
                code: $exception->response->getStatusCode(),
                previous: $exception,
            );
        }
    }

    private function getEndpointUrl(string $endpoint): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->apiUrl, '/'),
            ltrim($endpoint, '/'),
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function completeAndCleanGetParams(array $params): array
    {
        return array_filter(
            array_merge($params, [
                self::API_KEY_PARAM => $this->apiKey,
            ]),
            static fn(mixed $value): bool => !empty($value),
        );
    }
}
