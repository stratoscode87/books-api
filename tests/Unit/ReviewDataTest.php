<?php

namespace Tests\Unit;

use App\Clients\BooksClient\Enums\CoverSize;
use App\Clients\BooksClient\OpenLibraryClient\Exceptions\AuthorNotFoundException;
use App\Clients\BooksClient\OpenLibraryClient\OpenLibraryClient;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class ReviewDataTest extends TestCase
{
    public function testReviewDataMethodReturnsTheCorrectData(): void
    {
        $authorsOpenLibraryObj = (object) [
            (object) ['author' => (object) ['key' => 'FAKEAUTHOR1'],
            ],
        ];
        $expectedResult = [
            'title' => 'Trainspotting',
            'description' => 'Fake description',
            'authors' => 'Irvine Welsh',
            'cover_url' => 'https://example.com/books/irvinewelsh.jpg',
        ];

        $mock = $this->partialMock(OpenLibraryClient::class, function (MockInterface $mock) use ($expectedResult) {
            $mock->shouldReceive('authorNameFromWorkAuthor')
                ->once()
                ->andReturn($expectedResult['authors']);

            $mock->shouldReceive('coverUrlFromId')
                ->once()
                ->andReturn($expectedResult['cover_url']);
        });

        $result = $mock->reviewData((object) [
            'title' => 'Trainspotting',
            'description' => 'Fake description',
            'covers' => ['FAKECOVERID1', 'FAKECOVERID2'],
            'authors' => $authorsOpenLibraryObj,
        ], CoverSize::Medium);

        $this->assertEquals([
            'title' => $expectedResult['title'],
            'description' => $expectedResult['description'],
            'authors' => $expectedResult['authors'],
            'cover_img' => $expectedResult['cover_url'],
        ], $result);
    }

    public function testAuthorNameMethodReturnsTheCorrectName(): void
    {
        self::clearExistingFakes();
        $openLibraryClient = new OpenLibraryClient;

        //Fake the result of Open Library author API
        $openLibraryResponse = ['name' => 'Irvine Welsh'];
        Http::fake([
            $openLibraryClient->getUrl().'/authors/*' => Http::response($openLibraryResponse, 200, ['Headers']),
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
            $openLibraryClient->getUrl().'/authors/*' => Http::response(['error' => 'notfound'], 200, ['Headers']),
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
