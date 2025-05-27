<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeckControllerTest extends WebTestCase
{
    public function testDeckReturnsFullDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $cards = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(52, $cards);
    }

    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $cards = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(52, $cards);
    }

    public function testDrawOneCardFromDeck(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle'); // ensure deck is initialized

        $client->request('POST', '/api/deck/draw');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('drawn', $data);
        $this->assertArrayHasKey('remaining', $data);
        $this->assertEquals(51, $data['remaining']);
    }

    public function testDrawMultipleCards(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle'); // reset deck

        $client->request('POST', '/api/deck/draw/5');
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(5, $data['drawn']);
        $this->assertEquals(47, $data['remaining']);
    }
}
