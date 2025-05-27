<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $book = new Book();

        $this->assertNull($book->getId());

        $book->setTitle('Test Book Title');
        $this->assertSame('Test Book Title', $book->getTitle());

        $book->setIsbn('1234567890123');
        $this->assertSame('1234567890123', $book->getIsbn());

        $book->setAuthor('Test Author');
        $this->assertSame('Test Author', $book->getAuthor());

        $book->setImage('image.jpg');
        $this->assertSame('image.jpg', $book->getImage());
    }
}
