<?php

namespace App\Clients\BooksClient;

use App\Clients\BooksClient\Enums\CoverSize;

interface BooksClientInterface
{
    public function search(string $keywords);

    public function fetchWork(string $workId);

    public function reviewData(object $workData, CoverSize $imageSize);
}
