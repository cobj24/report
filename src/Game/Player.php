<?php

// src/Game/Player.php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Player
{
    protected CardHand $hand;

    public function __construct()
    {
        $this->hand = new CardHand();
    }

    public function drawCard(DeckOfCards $deck): void
    {
        $cards = $deck->draw();
        foreach ($cards as $card) {
            $this->hand->addCard($card);
        }
    }

    public function getScore(): int
    {
        $score = 0;
        foreach ($this->hand->getCards() as $card) {
            $value = $card->getValue();
            if (is_numeric($value)) {
                $score += (int)$value;
            } elseif (in_array($value, ['J', 'Q', 'K'])) {
                $score += 10;
            } elseif ($value === 'A') {
                $score += ($score + 14 <= 21) ? 14 : 1;
            }
        }
        return $score;
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }
}
