<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

class CardTest extends TestCase
{
    public function testCardCreation(): void
    {
        $card = new Card("Hearts", "Ace");

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals("Hearts", $card->getSuit());
        $this->assertEquals("Ace", $card->getValue());
        $this->assertEquals("Ace of Hearts", (string)$card);
    }
}
