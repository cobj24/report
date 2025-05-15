<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Player;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class PlayerTest extends TestCase
{
    public function testInitialScoreIsZero(): void
    {
        $player = new Player();
        $this->assertEquals(0, $player->getScore());
    }

    public function testScoreWithNumericCards(): void
    {
        $player = new Player();

        $hand = $player->getHand();
        $hand->addCard(new Card("Hearts", "2"));
        $hand->addCard(new Card("Diamonds", "5"));

        $this->assertEquals(7, $player->getScore());
    }

    public function testScoreWithFaceCards(): void
    {
        $player = new Player();

        $hand = $player->getHand();
        $hand->addCard(new Card("Hearts", "K"));
        $hand->addCard(new Card("Clubs", "J"));

        $this->assertEquals(20, $player->getScore());
    }

    public function testScoreWithAceUnder21(): void
    {
        $player = new Player();

        $hand = $player->getHand();
        $hand->addCard(new Card("Spades", "A"));
        $hand->addCard(new Card("Clubs", "6"));

        $this->assertEquals(17, $player->getScore());
    }

    public function testScoreWithAceOver21(): void
    {
        $player = new Player();

        $hand = $player->getHand();
        $hand->addCard(new Card("Hearts", "A"));
        $hand->addCard(new Card("Spades", "10"));
        $hand->addCard(new Card("Clubs", "10"));

        $score = $player->getScore();
        $this->assertEquals(21, $score);
    }

    public function testDrawCardFromDeck(): void
    {
        $player = new Player();
        $deck = new DeckOfCards();

        $initialCount = $deck->count();
        $player->drawCard($deck);

        $this->assertEquals($initialCount - 1, $deck->count());
        $this->assertGreaterThan(0, $player->getScore());
    }
}
