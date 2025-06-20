<?php

namespace App\Card;

/**
 * Class CardGraphic.
 *
 * Represents a playing card with visual enhancements such as symbols and image references.
 * Extends the basic Card class by adding graphical output functionality.
 */
class CardGraphic extends Card
{
    /**
     * Returns a string representation of the card using a Unicode suit symbol.
     *
     * @return string The formatted string, e.g. "[Q♥]".
     */
    public function __toString(): string
    {
        $suits = [
            'Hearts' => '♥',
            'Diamonds' => '♦',
            'Clubs' => '♣',
            'Spades' => '♠',
        ];

        return "[{$this->value}{$suits[$this->suit]}]";
    }

    /**
     * Gets the Unicode symbol representing the suit of the card.
     *
     * @return string one of ♥, ♦, ♣, ♠
     */
    public function getSymbol(): string
    {
        $suits = [
            'Hearts' => '♥',
            'Diamonds' => '♦',
            'Clubs' => '♣',
            'Spades' => '♠',
        ];

        return $suits[$this->suit];
    }

    /**
     * Returns a filename-friendly version of the card's name,
     * matching the image assets naming convention.
     *
     * @return string for example: "7h" for 7 of Hearts, "qs" for Queen of Spades
     */
    public function getImageBaseName(): string
    {
        $suitMap = [
            'Hearts' => 'h',
            'Diamonds' => 'd',
            'Clubs' => 'c',
            'Spades' => 's',
        ];

        $valueMap = [
            'Jack' => 'j',
            'Queen' => 'q',
            'King' => 'k',
            'Ace' => 'a',
        ];

        $value = $valueMap[$this->value] ?? strtolower($this->value);
        $suit = $suitMap[$this->suit] ?? '';

        return $value.$suit;
    }
}
