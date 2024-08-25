<?php

namespace Tests\Unit;

use App\Clients\BooksClient\OpenLibraryClient\Exceptions\AuthorNotFoundException;
use App\Clients\BooksClient\OpenLibraryClient\OpenLibraryClient;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class ReviewDataTest extends TestCase
{
    public function testAuthorNameMethodReturnsTheCorrectName(): void
    {
        self::clearExistingFakes();
        $openLibraryClient = new OpenLibraryClient;

        //Fake the result of Open Library author API
        $openLibraryResponse = ['name' => 'Irvine Welsh'];
        Http::fake([
            $openLibraryClient->getUrl().'authors/*' => Http::response($openLibraryResponse, 200, ['Headers']),
        ]);
        $name = $openLibraryClient->authorNameFromWorkAuthor('FAKEAUTHORID');

        $this->assertEquals('Irvine Welsh', $name);
    }

    public function testAuthorNameNotFoundException(): void
    {
        self::clearExistingFakes();
        $this->expectException(AuthorNotFoundException::class);
        $openLibraryClient = new OpenLibraryClient;

        //Fake Open Library author not found response
        Http::fake([
            $openLibraryClient->getUrl().'authors/*' => Http::response(['error' => 'notfound'], 200, ['Headers']),
        ]);

        $openLibraryClient->authorNameFromWorkAuthor('FAKEAUTHORID');
    }

    private static function clearExistingFakes(): void
    {
        $reflection = new \ReflectionObject(Http::getFacadeRoot());
        $property = $reflection->getProperty('stubCallbacks');
        $property->setAccessible(true);
        $property->setValue(Http::getFacadeRoot(), collect());
    }
}
