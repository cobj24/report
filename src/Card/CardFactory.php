<?php

namespace App\Card;

/**
 * Class CardFactory.
 *
 * Factory class for creating Card or CardGraphic objects,
 * depending on configuration.
 */
class CardFactory
{
    /**
     * Whether to create graphic cards or plain text cards.
     */
    private bool $useGraphics;

    /**
     * CardFactory constructor.
     *
     * @param bool $useGraphics true to return CardGraphic instances, false for Card
     */
    public function __construct(bool $useGraphics = false)
    {
        $this->useGraphics = $useGraphics;
    }

    /**
     * Creates a card instance based on the factory's setting.
     *
     * @param string $suit  The suit of the card (e.g. Hearts).
     * @param string $value The value of the card (e.g. 10, Queen).
     *
     * @return Card|CardGraphic a new instance of Card or CardGraphic
     */
    public function createCard(string $suit, string $value)
    {
        if ($this->useGraphics) {
            return new CardGraphic($suit, $value);
        }

        return new Card($suit, $value);
    }
}
