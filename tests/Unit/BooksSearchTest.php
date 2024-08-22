<?php

namespace Tests\Unit;

use App\Clients\BooksClient\Exceptions\OpenLibraryNotReachableException;
use App\Clients\BooksClient\OpenLibraryClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BooksSearchTest extends TestCase
{
    public function testItThrowsAnExceptionIfApiCallFails()
    {
        $booksClient = new OpenLibraryClient;
        Http::fake([
            '*' => fn ($request) => new RejectedPromise(
                new ConnectException('Test', $request->toPsrRequest())
            ),
        ]);

        $this->expectException(OpenLibraryNotReachableException::class);

        $booksClient->search('the lord of rings');
    }

    public function testOpenLibrarySearchApiResponseIsEqualToMethodResponse(): void
    {
        $booksClient = new OpenLibraryClient;

        $openLibraryResponse = ['foo' => 'bar'];

        Http::fake([
            $booksClient->getUrl().'/*' => Http::response($openLibraryResponse, 200, ['Headers']),
        ]);

        $response = $booksClient->search('the lord of rings');

        $this->assertEquals((object) $openLibraryResponse, $response);
    }
}
