<?php

namespace Tests\Unit;

use App\Clients\BooksClient\OpenLibraryClient\OpenLibraryClient;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class ReviewDataTest extends TestCase
{
    public function testAuthorNameMethodReturnsTheCorrectName(): void
    {
        $openLibraryClient = new OpenLibraryClient;

        //Fake the result of Open Library author API
        $openLibraryResponse = ['name' => 'Irvine Welsh'];
        Http::fake([
            $openLibraryClient->getUrl().'authors/*' => Http::response($openLibraryResponse, 200, ['Headers']),
        ]);

        $name = $openLibraryClient->authorNameFromWorkAuthor('OL23919A');

        $this->assertEquals('Irvine Welsh', $name);
    }
}
