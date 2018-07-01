<?php

namespace App\Tests\Library\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLimitedItemList()
    {
        $this->client->request('GET', '/api/v1/items/?limit=1');
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $dataResponse = json_decode($response->getContent());

        $this->assertCount(1, $dataResponse);
    }

    public function testUnlimitedItemList()
    {
        $this->client->request('GET', '/api/v1/items/');
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $dataResponse = json_decode($response->getContent());

        $this->assertGreaterThanOrEqual(1, $dataResponse);
    }

    public function testItemDetail()
    {
        $this->client->request('GET', '/api/v1/items/07ed651f-500c-45d1-99a4-65fbaf302494');

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $dataResponse = json_decode($response->getContent());

        $this->assertEquals('Lord of the Rings', $dataResponse->title);
    }

    /**
     * @todo: Created item should be removed or discarded after the test execution
     */
    public function testItemCreation()
    {
        $data = array(
            'title' => 'Test title',
            'image' => 'test image',
            'author' => 'test author',
            'price' => 42.32
        );

        $this->client->request(
            'POST',
            '/api/v1/items',
            $data
        );

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(201, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $dataResponse = json_decode($response->getContent());

        $this->assertNotEmpty($dataResponse->id);
        $this->assertEquals('Test title', $dataResponse->title);
    }
}