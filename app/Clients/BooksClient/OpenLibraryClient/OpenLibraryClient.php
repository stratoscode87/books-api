<?php

namespace App\Clients\BooksClient\OpenLibraryClient;

use App\Clients\BooksClient\BooksClientInterface;
use App\Clients\BooksClient\Enums\CoverSize;
use App\Clients\BooksClient\OpenLibraryClient\Exceptions\AuthorNotFoundException;
use App\Clients\BooksClient\OpenLibraryClient\Exceptions\OpenLibraryNotReachableException;
use App\Clients\BooksClient\OpenLibraryClient\Exceptions\WorkNotFoundException;
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
    private function getRequest(string $endpoint, array $params = []): object
    {
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
    public function search(string $keywords): object
    {
        $endpoint = $this->origin.'/search.json';

        $params = [
            'q' => $this->normalizeKeywords($keywords),
            'fields' => $this->generateFieldsParameters(),
        ];

        return $this->getRequest($endpoint, $params);
    }

    /**
     * @throws OpenLibraryNotReachableException|WorkNotFoundException
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

    public function reviewData(object $workData, string $imageSize): array
    {
        $coverUrl = $this->coverUrlFromId($workData?->covers[0], $imageSize);

    }

    private function normalizeKeywords(string $keywords): string
    {
        return str_replace(' ', '+', $keywords);
    }

    private function generateFieldsParameters(): string
    {
        return implode(',', self::SEARCH_RESPONSE_FIELDS);
    }

    private function coverUrlFromId(string $coverId, CoverSize $size): string
    {
        return $this->origin."/b/id/$coverId-$size->value.json";
    }

    private function authorsFromId(string $authorId): string
    {
        $endpoint = $this->origin.'/authors/'.$authorId.'.json';

        $response = $this->getRequest($endpoint);

        if (isset($response->error) && $response->error === 'notfound') {
            throw new AuthorNotFoundException('Work ID not found.');
        }

        return $response->object();
    }
}
