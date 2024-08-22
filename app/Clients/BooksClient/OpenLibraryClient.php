<?php

namespace App\Clients\BooksClient;

use App\Clients\BooksClient\Exceptions\OpenLibraryNotReachableException;
use App\Clients\BooksClient\Exceptions\WorkNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class OpenLibraryClient implements BooksClientInterface
{
    const array SEARCH_RESPONSE_FIELDS = [
        'key',
        'title',
        'author_name',
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

    /**
     * @throws OpenLibraryNotReachableException
     */
    public function search(string $keywords): object
    {
        $endpoint = $this->origin.'/search.json';

        $params = [
            'q' => $this->normalizeKeywords($keywords),
            'fields' => $this->generateFieldsParameters(),
        ];
        try {
            $response = Http::get($endpoint, $params)->object();
        } catch (\Exception) {
            throw new OpenLibraryNotReachableException('Open library is not reachable, please try again later.');
        }

        return $response;
    }

    /**
     * @throws OpenLibraryNotReachableException
     */
    public function fetchWork(string $workId): object
    {
        $endpoint = $this->origin.'/works/'.$workId.'.json';

        try {
            $response = Http::get($endpoint)->object();
        } catch (\Exception) {
            throw new OpenLibraryNotReachableException('Open library is not reachable, please try again later.');
        }

        if (isset($response->error) && $response->error === 'notfound') {
            throw new WorkNotFoundException('Work ID not found.');
        }

        return $response;
    }

    private function normalizeKeywords(string $keywords): string
    {
        return str_replace(' ', '+', $keywords);
    }

    private function generateFieldsParameters(): string
    {
        return implode(',', self::SEARCH_RESPONSE_FIELDS);
    }
}
