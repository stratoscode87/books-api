<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewPostRequest;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function store(ReviewPostRequest $request, ReviewService $service)
    {
        $response = $service->getReview($request->work_id);

        return response()->json([
            'data' => $response,
        ]);
    }
}
