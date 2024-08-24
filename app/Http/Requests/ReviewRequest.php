<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'cover_img' => ['required'],
            'description' => ['required'],
            'authors' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
