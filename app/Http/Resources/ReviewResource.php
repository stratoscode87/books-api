<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Review */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'title' => $this->title,
            'cover_img' => $this->cover_img,
            'description' => $this->description,
            'authors' => $this->authors,
            'status' => $this->status,
            'publishedAt' => $this->publishedAt,
        ];
    }
}
