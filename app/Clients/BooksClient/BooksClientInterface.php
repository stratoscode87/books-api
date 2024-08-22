<?php

namespace App\Clients\BooksClient;

interface BooksClientInterface
{
    public function search(string $keywords);
}
