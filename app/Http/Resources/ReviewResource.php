<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Review */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'review' => $this->review,
            'cover_img' => $this->cover_img,
            'description' => $this->description,
            'authors' => $this->authors,
            'score' => $this->score,
            'created_at' => Carbon::create($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::create($this->updated_at)->toDateTimeString(),
        ];
    }
}
