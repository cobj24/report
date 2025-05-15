<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

class CardHandTest extends TestCase
{
    public function testAddCard(): void
    {
        $hand = new CardHand();
        $card = new Card("Hearts", "Ace");

        $hand->addCard($card);
        $this->assertCount(1, $hand->getCards());
        $this->assertEquals("Ace", $hand->getCards()[0]->getValue());
    }

    public function testGetCards(): void
    {
        $hand = new CardHand();
        $card1 = new Card("Hearts", "Ace");
        $card2 = new Card("Spades", "King");

        $hand->addCard($card1);
        $hand->addCard($card2);

        $cards = $hand->getCards();
        $this->assertCount(2, $cards);
        $this->assertEquals("Ace", $cards[0]->getValue());
        $this->assertEquals("King", $cards[1]->getValue());
    }
}
