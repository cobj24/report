<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Bank;
use App\Card\Card;
use App\Card\DeckOfCards;

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
        $deck = new DeckOfCards();

        $initialDeckCount = $deck->count();
        $bank->drawCard($deck);

        $this->assertLessThan($initialDeckCount, $deck->count());
        $this->assertGreaterThan(0, $bank->getScore());
    }

    public function testBankScoreWithFaceCardAndAce(): void
    {
        $bank = new Bank();
        $hand = $bank->getHand();

        $hand->addCard(new Card("Spades", "K")); // 10
        $hand->addCard(new Card("Hearts", "A")); // 14 (should fit)

        $this->assertEquals(24, $bank->getScore());
    }

    public function testBankScoreWithAceAdjustedToOne(): void
    {
        $bank = new Bank();
        $hand = $bank->getHand();

        $hand->addCard(new Card("Spades", "K")); // 10
        $hand->addCard(new Card("Diamonds", "Q")); // 10
        $hand->addCard(new Card("Hearts", "A")); // 1

        $this->assertEquals(21, $bank->getScore());
    }
}
