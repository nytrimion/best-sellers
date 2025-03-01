<?php

declare(strict_types=1);

namespace tests\Feature\Http\GetBestSellers;

use App\Http\GetBestSellers\GetBestSellersRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GetBestSellersRequestTest extends TestCase
{
    private GetBestSellersRequest $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut  = new GetBestSellersRequest();
    }

    static public function validParametersProvider(): iterable
    {
        yield 'no parameters' => [[]];
        yield 'author' => [[
            'author' => 'John',
        ]];
        yield 'isbn 10' => [[
            'isbn' => ['0553293389'],
        ]];
        yield 'isbn 13' => [[
            'isbn' => ['9780553293388'],
        ]];
        yield 'isbn list' => [[
            'isbn' => ['0553293389', '9780553293388'],
        ]];
        yield 'title' => [[
            'title' => 'whatever',
        ]];
        yield 'offset 0' => [[
            'offset' => '0',
        ]];
        yield 'offset 20' => [[
            'offset' => '20',
        ]];
    }

    #[DataProvider('validParametersProvider')]
    public function testItPassesValidation(array $parameters): void
    {
        $validator = Validator::make($parameters, $this->sut->rules());

        $this->assertTrue($validator->passes());
    }

    static public function invalidParametersProvider(): iterable
    {
        yield 'isbn 10 invalid' => [[
            'isbn' => ['5553293389'],
        ]];
        yield 'isbn 13 invalid' => [[
            'isbn' => ['7780553293388'],
        ]];
        yield 'isbn 9' => [[
            'isbn' => '006137422',
        ]];
        yield 'isbn 11' => [[
            'isbn' => '00613742299',
        ]];
        yield 'isbn 12' => [[
            'isbn' => '978044657993',
        ]];
        yield 'isbn 14' => [[
            'isbn' => '97804465799333',
        ]];
        yield 'offset -1' => [[
            'offset' => '-1',
        ]];
        yield 'offset 1' => [[
            'offset' => '1',
        ]];
        yield 'offset 19' => [[
            'offset' => '19',
        ]];
        yield 'offset 21' => [[
            'offset' => '21',
        ]];
    }

    #[DataProvider('invalidParametersProvider')]
    public function testItFailsValidation(array $parameters): void
    {
        $validator = Validator::make($parameters, $this->sut->rules());

        $this->assertFalse($validator->passes());
    }
}
