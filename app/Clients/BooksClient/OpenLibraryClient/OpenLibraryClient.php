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

    private string $coverOrigin;

    public function __construct()
    {
        $this->origin = Config::get('services.openlibrary.url');
        $this->coverOrigin = Config::get('services.openlibrary.cover_url');
    }

    public function getUrl()
    {
        return $this->origin;
    }

    public function getCoverUrl()
    {
        return $this->coverOrigin;
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
     * @throws OpenLibraryNotReachableException
     * @throws WorkNotFoundException
     */
    public function fetchWork(string $workId): object
    {
        $endpoint = $this->origin.'/works/'.$workId.'.json';

        $response = $this->getRequest($endpoint);

        if (isset($response->error) && $response->error === 'notfound') {
            throw new WorkNotFoundException('Work ID not found.');
        }

        return $response;
    }

    /**
     * @throws OpenLibraryNotReachableException
     * @throws AuthorNotFoundException
     */
    public function reviewData(object $workData, CoverSize $imageSize): array
    {
        $coverUrl = $this->coverUrlFromId($workData?->covers[0], $imageSize);
        $authors = '';
        foreach ($workData->authors as $author) {
            $authorName = $this->authorNameFromWorkAuthor($author->author->key);
            if (empty($authors)) {
                $authors = "$authorName";
            } else {
                $authors = "$authors, $authorName";
            }
        }

        //set $authors as null if is an empty string
        $authors = empty($authors) ? null : $authors;

        return [
            'title' => $workData->title,
            'description' => $workData->description,
            'authors' => $authors,
            'cover_img' => $coverUrl,
        ];
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
        return $this->coverOrigin."b/id/$coverId-$size->value.jpg";
    }

    /**
     * @throws AuthorNotFoundException
     * @throws OpenLibraryNotReachableException
     */
    private function authorNameFromWorkAuthor(string $authorId): string
    {
        $authorId = str_replace('/authors/', '', $authorId);

        $endpoint = $this->origin.'/authors/'.$authorId.'.json';

        $response = $this->getRequest($endpoint);

        if (isset($response->error) && $response->error === 'notfound') {
            throw new AuthorNotFoundException('Author ID not found.');
        }

        return $response->name;
    }
}
