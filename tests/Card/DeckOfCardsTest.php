<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;

class DeckOfCardsTest extends TestCase
{
    public function testDeckCreation(): void
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getCards());
    }

    public function testDeckCreationWithGraphics(): void
    {
        $deck = new DeckOfCards(true);
        $this->assertCount(52, $deck->getCards());
        $this->assertInstanceOf(CardGraphic::class, $deck->getCards()[0]);
    }

    public function testShuffle(): void
    {
        $deck = new DeckOfCards();
        $cardsBeforeShuffle = $deck->getCards();
        $deck->shuffle();
        $cardsAfterShuffle = $deck->getCards();

        $this->assertNotEquals($cardsBeforeShuffle, $cardsAfterShuffle);
    }

    public function testDrawCard(): void
    {
        $deck = new DeckOfCards();
        $drawnCards = $deck->draw(5);

        $this->assertCount(5, $drawnCards);
        $this->assertCount(47, $deck->getCards());
    }

    public function testDeckCount(): void
    {
        $deck = new DeckOfCards();
        $this->assertEquals(52, $deck->count());
    }
}
