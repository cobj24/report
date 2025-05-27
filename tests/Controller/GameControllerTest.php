<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class GameControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testDocPage()
    {
        $client = static::createClient();
        $client->request('GET', '/game/doc');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dokumentation');
    }

    public function testPlayPageWithoutActions()
    {
        $client = static::createClient();

        // Skapa nytt game-objekt, men dra inget kort för att simulera startläge
        $game = new \App\Game\Game21();

        // Spara game-objekt i sessionen
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', $game);
        $session->save();

        // Sätt cookies med sessionens ID
        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('GET', '/game/play');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card'); // Tomt kan vara ok men för säkerhet kan du tillåta att det finns kort
    }

    public function testPlayDrawAction()
    {
        $client = static::createClient();

        $game = new \App\Game\Game21();
        $game->playerDraw(); // dra minst ett kort

        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', $game);
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('POST', '/game/play', ['draw' => true]);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');

        // Status visas som <h3>
        $this->assertSelectorExists('h3');
    }

    public function testPlayStayAction()
    {
        $client = static::createClient();

        $game = new \App\Game\Game21();
        $game->playerDraw(); // dra ett kort så att spelet är igång

        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', $game);
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('POST', '/game/play', ['stay' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');
        $this->assertSelectorExists('h3'); // Status i <h3>
    }

    public function testResetGame()
    {
        $client = static::createClient();

        $game = new \App\Game\Game21();
        $game->playerDraw();

        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', $game);
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        // Dra kort för att simulera en aktiv runda
        $client->request('POST', '/game/play', ['draw' => true]);

        // Resetta spelet
        $client->request('GET', '/game/reset');

        $this->assertResponseRedirects('/game/play');

        $client->followRedirect();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');
    }
}
