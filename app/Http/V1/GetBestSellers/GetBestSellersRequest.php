<?php

declare(strict_types=1);

namespace App\Http\V1\GetBestSellers;

use Illuminate\Foundation\Http\FormRequest;

class GetBestSellersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'author' => ['sometimes'],
            'isbn' => ['sometimes', 'list'],
            'isbn.*' => ['isbn:10,13'],
            'title' => ['sometimes'],
            'offset' => ['sometimes', 'integer', 'gte:0', 'multiple_of:20'],
        ];
    }
}
