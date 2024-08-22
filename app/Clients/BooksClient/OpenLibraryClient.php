<?php

namespace App\Clients\BooksClient;

use App\Clients\BooksClient\BooksClientInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class OpenLibraryClient implements BooksClientInterface
{
    const array SEARCH_RESPONSE_FIELDS = [
        "key",
        "title",
        "author_name"
    ];

    private string $origin;

    public function __construct()
    {
        $this->origin = Config::get('services.openlibrary.url');
    }

    public function getUrl()
    {
        return $this->origin;
    }

    public function search(string $keywords): object
    {
        $endpoint = $this->origin . '/search.json';

        $params = [
            'q' => $this->normalizeKeywords($keywords),
            'fields' => $this->generateFieldsParameters(),
        ];

        return Http::get($endpoint, $params)->object();
    }

    private function normalizeKeywords(string $keywords) : string
    {
        return str_replace(' ', '+', $keywords);
    }

    private function generateFieldsParameters() : string {
        return implode(",", self::SEARCH_RESPONSE_FIELDS);
    }
}
