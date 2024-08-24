<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'exists:reviews,id',
            'work_id' => 'required|min:4',
            'review' => 'required|string|min:4|max:5000',
            'score' => 'required|integer|min:1|max:10',
            'title' => 'required',
            'cover_img' => 'required|url',
            'description' => 'required|string|min:4|max:5000',
            'authors' => 'required',
        ];
    }
}
