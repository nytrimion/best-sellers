<?php

declare(strict_types=1);

namespace tests\Feature\Http\V1\GetBestSellers;

use app\Http\V1\GetBestSellers\GetBestSellersRequest;
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

    /**
     * @return iterable<string, array{
     *     parameters: array<string, mixed>,
     * }>
     */
    static public function validParametersProvider(): iterable
    {
        yield 'no parameters' => [
            'parameters' => [],
        ];
        yield 'author' => [
            'parameters' => ['author' => 'John'],
        ];
        yield 'isbn 10' => [
            'parameters' => ['isbn' => ['0553293389']],
        ];
        yield 'isbn 13' => [
            'parameters' => ['isbn' => ['9780553293388']],
        ];
        yield 'isbn list' => [
            'parameters' => ['isbn' => ['0553293389', '9780553293388']],
        ];
        yield 'title' => [
            'parameters' => ['title' => 'whatever'],
        ];
        yield 'offset 0' => [
            'parameters' => ['offset' => '0'],
        ];
        yield 'offset 20' => [
            'parameters' => ['offset' => '20'],
        ];
    }

    /**
     * @param array<string, mixed> $parameters
     */
    #[DataProvider('validParametersProvider')]
    public function testItPassesValidation(array $parameters): void
    {
        $validator = Validator::make($parameters, $this->sut->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return iterable<string, array{
     *     parameters: array<string, mixed>,
     * }>
     */
    static public function invalidParametersProvider(): iterable
    {
        yield 'isbn 10 invalid' => [
            'parameters' => ['isbn' => ['5553293389']],
        ];
        yield 'isbn 13 invalid' => [
            'parameters' => ['isbn' => ['7780553293388']],
        ];
        yield 'isbn 9' => [
            'parameters' => ['isbn' => '006137422'],
        ];
        yield 'isbn 11' => [
            'parameters' => ['isbn' => '00613742299'],
        ];
        yield 'isbn 12' => [
            'parameters' => ['isbn' => '978044657993'],
        ];
        yield 'isbn 14' => [
            'parameters' => ['isbn' => '97804465799333'],
        ];
        yield 'offset -1' => [
            'parameters' => ['offset' => '-1'],
        ];
        yield 'offset 1' => [
            'parameters' => ['offset' => '1'],
        ];
        yield 'offset 19' => [
            'parameters' => ['offset' => '19'],
        ];
        yield 'offset 21' => [
            'parameters' => ['offset' => '21'],
        ];
    }

    /**
     * @param array<string, mixed> $parameters
     */
    #[DataProvider('invalidParametersProvider')]
    public function testItFailsValidation(array $parameters): void
    {
        $validator = Validator::make($parameters, $this->sut->rules());

        $this->assertFalse($validator->passes());
    }
}
