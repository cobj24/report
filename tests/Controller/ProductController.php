<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/product');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'ProductController'); // Anpassa efter din vy
    }

    public function testCreateProduct()
    {
        $client = static::createClient();
        $client->request('GET', '/product/create');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Saved new product with id', $client->getResponse()->getContent());
    }

    public function testShowAllProduct()
    {
        $client = static::createClient();
        $client->request('GET', '/product/show');
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testShowProductById()
    {
        $client = static::createClient();

        // Först skapa en produkt för att ha ett id att testa
        $crawler = $client->request('GET', '/product/create');
        preg_match('/id (\d+)/', $client->getResponse()->getContent(), $matches);
        $id = $matches[1] ?? null;
        $this->assertNotNull($id);

        // Testa att hämta produkten via JSON
        $client->request('GET', "/product/show/$id");
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        // Testa 404 för icke-existerande id
        $client->request('GET', '/product/show/99999999');
        $this->assertResponseStatusCodeSame(200); // Den returnerar JSON null, kan anpassas om exception
    }

    public function testDeleteProductById()
    {
        $client = static::createClient();

        // Skapa produkt för test
        $client->request('GET', '/product/create');
        preg_match('/id (\d+)/', $client->getResponse()->getContent(), $matches);
        $id = $matches[1] ?? null;
        $this->assertNotNull($id);

        // Radera produkten
        $client->request('GET', "/product/delete/$id");
        $this->assertResponseRedirects('/product/show');

        // Testa radera icke-existerande produkt
        $client->request('GET', '/product/delete/99999999');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testUpdateProduct()
    {
        $client = static::createClient();

        // Skapa produkt
        $client->request('GET', '/product/create');
        preg_match('/id (\d+)/', $client->getResponse()->getContent(), $matches);
        $id = $matches[1] ?? null;
        $this->assertNotNull($id);

        // Uppdatera produktvärde
        $client->request('GET', "/product/update/$id/555");
        $this->assertResponseRedirects('/product/show');

        // Testa update icke-existerande produkt
        $client->request('GET', '/product/update/99999999/555');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewAllProduct()
    {
        $client = static::createClient();
        $client->request('GET', '/product/view');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.product-list');
    }

    public function testViewProductWithMinimumValue()
    {
        $client = static::createClient();
        $client->request('GET', '/product/view/100');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.product-list');
    }
}
