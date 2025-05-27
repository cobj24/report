<?php

namespace App\Card;

class CardFactory
{
    private bool $useGraphics;

    public function __construct(bool $useGraphics = false)
    {
        $this->useGraphics = $useGraphics;
    }

    public function createCard(string $suit, string $value)
    {
        if ($this->useGraphics) {
            return new CardGraphic($suit, $value);
        }

        return new Card($suit, $value);
    }
}
