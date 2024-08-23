<?php

namespace App\Clients\BooksClient;

interface BooksClientInterface
{
    public function search(string $keywords);

    public function fetchWork(string $workId);

    public function reviewData(object $workData, string $imageSize);
}
