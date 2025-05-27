<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;
use App\Card\CardFactory;
use App\Game\Player;

class DeckOfCardsTest extends TestCase
{
    public function testDeckCreation(): void
    {
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory);
        $this->assertCount(52, $deck->getCards());
    }

    public function testDeckCreationWithGraphics(): void
    {
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory, true);
        $this->assertCount(52, $deck->getCards());
        $this->assertInstanceOf(CardGraphic::class, $deck->getCards()[0]);
    }

    public function testShuffle(): void
    {
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory);
        $cardsBeforeShuffle = $deck->getCards();
        $deck->shuffle();
        $cardsAfterShuffle = $deck->getCards();

        $this->assertNotEquals($cardsBeforeShuffle, $cardsAfterShuffle);
    }

    public function testDrawCardFromDeck(): void
    {
        $player = new Player();
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory);

        $initialCount = $deck->count();
        $player->drawCard($deck);

        $this->assertEquals($initialCount - 1, $deck->count());
        $this->assertGreaterThan(0, $player->getScore());
    }

    public function testDeckCount(): void
    {
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory);
        $this->assertEquals(52, $deck->count());
    }
}
