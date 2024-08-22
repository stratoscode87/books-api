<?php

namespace App\Services;

use App\Clients\BooksClient\BooksClientInterface;

class ReviewService
{
    public function __construct(private readonly BooksClientInterface $booksClient) {}

    public function getReview(string $workId)
    {

        return $this->booksClient->fetchWork($workId);
    }
}
