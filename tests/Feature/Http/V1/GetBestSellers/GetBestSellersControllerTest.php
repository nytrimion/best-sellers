<?php

declare(strict_types=1);

namespace tests\Feature\Http\V1\GetBestSellers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetBestSellersControllerTest extends TestCase
{
    private const string ENDPOINT_URL = '/api/v1/best-sellers';

    public function testItReturnsSuccessfulResponseWithNoGivenParameters(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'OK'], 200),
        ]);
        $this
            ->getJson(self::ENDPOINT_URL)
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);
    }

    public function testItReturnsSuccessfulResponseWithAllGivenParameters(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'OK'], 200),
        ]);
        $this
            ->getJson(self::ENDPOINT_URL . '?author=John&title=Whatever&isbn[]=0553293389&isbn[]=9780553293388&offset=20')
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);
    }

    public function testItReturnsValidationErrorsWhenGivenParameterAreInvalid(): void
    {
        $this
            ->getJson(self::ENDPOINT_URL . '?isbn[]=055329338&isbn[]=97805532933888&offset=10')
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'isbn.0' => ['isbn.0 must be a valid International Standard Book Number (ISBN).'],
                'isbn.1' => ['isbn.1 must be a valid International Standard Book Number (ISBN).'],
                'offset' => ['The offset field must be a multiple of 20.'],
            ]]);
    }

    public function testItReturnsServerErrorWhenHttpConnectionHasFailed(): void
    {
        Http::fake([
            '*' => Http::failedConnection(),
        ]);
        $this->getJson(self::ENDPOINT_URL)->assertStatus(500);
    }

    public function testItReturnsServerErrorWhenHttpResponseHasFailed(): void
    {
        Http::fake([
            '*' => Http::response([], 429),
        ]);
        $this->getJson(self::ENDPOINT_URL)->assertStatus(500);
    }

    public function testItReturnsServerErrorWhenHttpResponseContentIsUnexpected(): void
    {
        Http::fake([
            '*' => Http::response('string', 200),
        ]);
        $this->getJson(self::ENDPOINT_URL)->assertStatus(500);
    }
}
