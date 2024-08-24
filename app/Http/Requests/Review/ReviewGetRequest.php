<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class ReviewGetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'exists:reviews,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
