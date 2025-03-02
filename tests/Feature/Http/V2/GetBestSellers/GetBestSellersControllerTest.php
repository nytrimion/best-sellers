<?php

declare(strict_types=1);

namespace tests\Feature\Http\V2\GetBestSellers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetBestSellersControllerTest extends TestCase
{
    private const string ENDPOINT_URL = '/api/v2/best-sellers';

    public function testItReturnsSuccessfulResponseWhenCalledTwiceWithNoGivenParameters(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'OK'], 200),
        ]);
        $this
            ->getJson(self::ENDPOINT_URL)
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);
        $this
            ->getJson(self::ENDPOINT_URL)
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);

        Http::assertSentCount(1);
    }

    public function testItReturnsSuccessfulResponseWhenCalledTwiceWithAllGivenParameters(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'OK'], 200),
        ]);
        $this
            ->getJson(self::ENDPOINT_URL . '?author=John&title=Whatever&isbn[]=0553293389&isbn[]=9780553293388&offset=20')
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);
        $this
            ->getJson(self::ENDPOINT_URL . '?author=John&title=Whatever&isbn[]=0553293389&isbn[]=9780553293388&offset=20')
            ->assertStatus(200)
            ->assertJson(['status' => 'OK']);

        Http::assertSentCount(1);
    }
}
