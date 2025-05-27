<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    public function testLuckyRouteReturnsValidJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/lucky');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('lucky_number', $data);
        $this->assertGreaterThanOrEqual(1, $data['lucky_number']);
        $this->assertLessThanOrEqual(100, $data['lucky_number']);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('timestamp', $data);
    }

    public function testQuoteRouteReturnsRandomQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('quote', $data);
        $this->assertNotEmpty($data['quote']);
    }

    public function testApiLandingPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
}
