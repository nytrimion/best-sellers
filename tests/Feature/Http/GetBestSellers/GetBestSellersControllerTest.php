<?php

declare(strict_types=1);

namespace tests\Feature\Http\GetBestSellers;

use Tests\TestCase;

class GetBestSellersControllerTest extends TestCase
{
    private const string ENDPOINT_URL = '/api/best-sellers';

    public function testItReturnsResponseWithStatusOkWhenGivenParametersAreValid(): void
    {
        $response = $this->getJson(
            self::ENDPOINT_URL . '?author=Isaac&isbn[]=0553293389&isbn[]=9780553293388&title=Whatever&offset=20',
        );
        $response->assertStatus(200);
        $response->assertJson(['foo' => 'bar']);
    }

    public function testItReturnsValidationErrorsWhenGivenParameterAreInvalid(): void
    {
        $response = $this->getJson(
            self::ENDPOINT_URL . '?isbn[]=055329338&isbn[]=97805532933888&offset=10',
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'isbn.0' => ['isbn.0 must be a valid International Standard Book Number (ISBN).'],
                'isbn.1' => ['isbn.1 must be a valid International Standard Book Number (ISBN).'],
                'offset' => ['The offset field must be a multiple of 20.'],
            ],
        ]);
    }
}
