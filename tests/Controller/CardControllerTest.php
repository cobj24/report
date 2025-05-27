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
        $this->assertSelectorExists('h1'); // Anpassa beroende på innehåll i din vy
    }

    public function testDeckPage()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card'); // Exempel: du visar kort med class "card"
    }

    public function testShuffleDeckResetsDeck()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card'); // Kort finns efter shuffle
    }

    public function testDrawOneCardReducesDeck()
    {
        $client = static::createClient();

        // Initiera session genom att hämta deck först (för att lägga in i session)
        $client->request('GET', '/card/deck');

        // Dra ett kort
        $client->request('GET', '/card/deck/draw');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-card'); // Beroende på hur du visar draget kort
        $this->assertSelectorTextContains('.remaining-count', '51'); // Exempel på kvarvarande kort
    }

    public function testDrawNumberOfCards()
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        // Dra 5 kort
        $client->request('GET', '/card/deck/draw/5');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-card'); // Kontrollera kort visas
        $this->assertSelectorTextContains('.remaining-count', '47'); // 52 - 5 = 47
    }

    public function testSessionDelete()
    {
        $client = static::createClient();

        // Lägg något i sessionen (besök deck-sida)
        $client->request('GET', '/card/deck');

        // Radera sessionen
        $client->request('GET', '/session/delete');

        $this->assertResponseRedirects('/card');

        $client->followRedirect();
        $this->assertSelectorExists('.flash-success'); // Meddelande om session raderad
    }

    public function testSessionDebug()
    {
        $client = static::createClient();

        // Initiera session genom att hämta deck först
        $client->request('GET', '/card/deck');

        // Debug session
        $client->request('GET', '/session');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('pre'); // Anta du visar session dump i <pre>
    }
}
