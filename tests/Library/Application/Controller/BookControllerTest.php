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

    public function testItemList()
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

        $data = json_decode($response->getContent());

        $this->assertCount(1, $data);
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

        $data = json_decode($response->getContent());

        $this->assertEquals('Lord of the Rings', $data->title);
    }
}