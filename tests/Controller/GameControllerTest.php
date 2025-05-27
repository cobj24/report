<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1'); // Kolla att startsidan visar något tydligt
    }

    public function testDocPage()
    {
        $client = static::createClient();
        $client->request('GET', '/game/doc');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dokumentation'); // Anpassa efter ditt innehåll
    }

    public function testPlayPageWithoutActions()
    {
        $client = static::createClient();
        $client->request('GET', '/game/play');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.player-hand'); // Kontrollera att spelardata finns med i sidan
    }

    public function testPlayDrawAction()
    {
        $client = static::createClient();

        // Skicka POST med "draw"
        $client->request('POST', '/game/play', ['draw' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.player-hand');
        $this->assertSelectorExists('.bank-hand');
        $this->assertSelectorExists('.status');
        // Kan lägga till asserts för att se att handen uppdaterats om du kan identifiera det i HTML
    }

    public function testPlayStayAction()
    {
        $client = static::createClient();

        // Skicka POST med "stay"
        $client->request('POST', '/game/play', ['stay' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.player-hand');
        $this->assertSelectorExists('.bank-hand');
        $this->assertSelectorExists('.status');
    }

    public function testResetGame()
    {
        $client = static::createClient();

        // Initiera spel i session via draw (för att se till att det finns ett spel)
        $client->request('POST', '/game/play', ['draw' => true]);

        // Nollställ spelet
        $client->request('GET', '/game/reset');

        $this->assertResponseRedirects('/game/play');

        $client->followRedirect();
        $this->assertSelectorExists('.player-hand'); // Nu ska spelet vara nollställt och nytt spel laddat
    }
}
