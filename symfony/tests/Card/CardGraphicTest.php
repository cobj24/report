<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

class CardGraphicTest extends TestCase
{
    public function testCardGraphicToString(): void
    {
        $card = new CardGraphic("Hearts", "Ace");
        $this->assertEquals("[Ace♥]", (string)$card);

        $card = new CardGraphic("Spades", "10");
        $this->assertEquals("[10♠]", (string)$card);

        $card = new CardGraphic("Diamonds", "King");
        $this->assertEquals("[King♦]", (string)$card);

        $card = new CardGraphic("Clubs", "2");
        $this->assertEquals("[2♣]", (string)$card);
    }
}
