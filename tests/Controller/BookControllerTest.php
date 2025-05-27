<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testIndexPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1'); // Kolla att sidan innehåller något (t.ex. titel)
        $this->assertSelectorTextContains('h1', ''); // Om du vill kolla specifik text i h1
    }

    public function testNewBookFormDisplaysAndSubmits()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/library/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Save')->form(); // Anpassa knappen om den heter annat

        $form['book_form[title]'] = 'Test Book Title';
        $form['book_form[isbn]'] = '1234567890123';
        $form['book_form[author]'] = 'Test Author';
        $form['book_form[image]'] = 'test.jpg';

        $client->submit($form);

        $this->assertResponseRedirects('/library'); // Redirect efter submit
        $client->followRedirect();

        $this->assertSelectorExists('.flash-success'); // Om du har flash-meddelanden
    }

    public function testShowBookPage()
    {
        $client = static::createClient();

        // Förutsätter att bok med id=1 finns (annars mocka eller skapa testdata)
        $client->request('GET', '/library/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.book-title'); // Anpassa efter din vy
    }

    // Kan lägga till test för edit, delete och reset på liknande sätt
}
