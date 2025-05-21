<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = [
            ['title' => 'Kafka på stranden', 'isbn' => '9789113132969', 'author' => 'Haruki Murakami', 'image' => 'img/kafka.jpg'],
            ['title' => 'Illusionisten', 'isbn' => '9789178935123', 'author' => 'John Fowles', 'image' => 'img/illusionisten.jpg'],
            ['title' => 'M Train', 'isbn' => '9781408867709', 'author' => 'Patti Smith', 'image' => 'img/mtrain.jpg'],
        ];

        foreach ($books as $b) {
            $book = new Book();
            $book->setTitle($b['title']);
            $book->setIsbn($b['isbn']);
            $book->setAuthor($b['author']);
            $book->setImage($b['image']);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
