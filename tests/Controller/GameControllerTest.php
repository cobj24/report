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
        $client->request('GET', '/game/play');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-cards');  // ändrat från .player-hand
        $this->assertSelectorExists('.playing-card'); // kolla att det finns kort
    }

    public function testPlayDrawAction()
    {
        $client = static::createClient();

        // Skapa session och sätt cookie
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', null); // Rensa eventuell tidigare game-objekt
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('POST', '/game/play', ['draw' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');
        $this->assertSelectorExists('.status');
    }

    public function testPlayStayAction()
    {
        $client = static::createClient();

        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', null);
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('POST', '/game/play', ['stay' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');
        $this->assertSelectorExists('.status');
    }

    public function testResetGame()
    {
        $client = static::createClient();

        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('game', null);
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        $client->request('POST', '/game/play', ['draw' => true]);

        $client->request('GET', '/game/reset');

        $this->assertResponseRedirects('/game/play');

        $client->followRedirect();
        $this->assertSelectorExists('.drawn-cards');
        $this->assertSelectorExists('.playing-card');
    }
}
