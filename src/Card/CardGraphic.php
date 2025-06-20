<?php

namespace App\Card;

class CardGraphic extends Card
{
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

        return $value . $suit;
    }
}
