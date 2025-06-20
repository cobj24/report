<?php

namespace App\Card;

/**
 * Class CardHand.
 *
 * Represents a hand of playing cards.
 */
class CardHand
{
    /**
     * @var Card[] array of Card objects representing the hand
     */
    private array $cards = [];

    /**
     * Adds a card to the hand.
     *
     * @param Card $card the card to add
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Returns all cards in the hand.
     *
     * @return Card[] an array of Card objects
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
