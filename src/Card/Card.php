<?php

namespace App\Card;

/**
 * Class Card.
 *
 * Represents a standard playing card with a suit and a value.
 */
class Card
{
    /**
     * The suit of the card (e.g. Hearts, Diamonds, Clubs, Spades).
     */
    protected string $suit;

    /**
     * The value of the card (e.g. 2–10, Jack, Queen, King, Ace).
     */
    protected string $value;

    /**
     * Card constructor.
     *
     * @param string $suit  the suit of the card
     * @param string $value the value of the card
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Get the suit of the card.
     *
     * @return string the card's suit
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Get the value of the card.
     *
     * @return string the card's value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Return the card as a readable string.
     *
     * @return string example: "Ace of Spades"
     */
    public function __toString(): string
    {
        return "{$this->value} of {$this->suit}";
    }
}
