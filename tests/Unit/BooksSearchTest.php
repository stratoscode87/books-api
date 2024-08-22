<?php

namespace Tests\Unit;

use App\Clients\BooksClient\OpenLibraryClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BooksSearchTest extends TestCase
{

    public function testOpenLibrarySearchAPIResponseIsEqualToMethodResponse(): void
    {
        $booksClient = new OpenLibraryClient();

        $openLibraryResponse = ['foo' => 'bar'];

        Http::fake([
            $booksClient->getUrl().'/*' => Http::response($openLibraryResponse, 200, ['Headers']),
        ]);

        $response = $booksClient->search('the lord of rings');

        $this->assertEquals((object)$openLibraryResponse, $response);
    }
}
