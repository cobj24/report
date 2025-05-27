<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testDeckPage()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card');
    }

    public function testShuffleDeckResetsDeck()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card');
    }

    public function testDrawOneCardReducesDeck()
    {
        $client = static::createClient();

        $client->request('GET', '/card/deck');

        $client->request('GET', '/card/deck/draw');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-card');
        $this->assertSelectorTextContains('.remaining-count', '51');
    }

    public function testDrawNumberOfCards()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        $client->request('GET', '/card/deck/draw/5');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-card');
        $this->assertSelectorTextContains('.remaining-count', '47');
    }

    public function testSessionDelete()
    {
        $client = static::createClient();

        $client->request('GET', '/card/deck');

        $client->request('GET', '/session/delete');

        $this->assertResponseRedirects('/card');

        $client->followRedirect();
        $this->assertSelectorExists('.flash-success');
    }

    public function testSessionDebug()
    {
        $client = static::createClient();

        $client->request('GET', '/card/deck');

        $client->request('GET', '/session');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('pre');
    }
}
