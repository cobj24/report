<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Bank;
use App\Card\Card;
use App\Card\DeckOfCards;
use App\Card\CardFactory;


class BankTest extends TestCase
{
    public function testBankIsInstanceOfPlayer(): void
    {
        $bank = new Bank();
        $this->assertInstanceOf(\App\Game\Player::class, $bank);
    }

    public function testBankInitialScoreIsZero(): void
    {
        $bank = new Bank();
        $this->assertEquals(0, $bank->getScore());
    }

    public function testBankDrawsCardFromDeck(): void
    {
        $bank = new Bank();
        $factory = new CardFactory();
        $deck = new DeckOfCards($factory);

        $initialDeckCount = $deck->count();
        $bank->drawCard($deck);

        $this->assertLessThan($initialDeckCount, $deck->count());
        $this->assertGreaterThan(0, $bank->getScore());
    }

    public function testBankScoreWithFaceCardAndAce(): void
    {
        $bank = new Bank();
        $hand = $bank->getHand();

        $hand->addCard(new Card("Spades", "K"));
        $hand->addCard(new Card("Hearts", "A"));

        $score = $bank->getScore();
        $this->assertEquals(21, $score);
    }

    public function testBankScoreWithAceAdjustedToOne(): void
    {
        $bank = new Bank();
        $hand = $bank->getHand();

        $hand->addCard(new Card("Spades", "K"));
        $hand->addCard(new Card("Diamonds", "Q"));
        $hand->addCard(new Card("Hearts", "A"));

        $this->assertEquals(21, $bank->getScore());
    }
}
