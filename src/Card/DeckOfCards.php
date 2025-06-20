<?php

namespace App\Card;

/**
 * Class DeckOfCards.
 *
 * Represents a standard deck of playing cards.
 */
class DeckOfCards
{
    /**
     * @var Card[] array holding the cards in the deck
     */
    private array $cards = [];

    /**
     * DeckOfCards constructor.
     *
     * Initializes the deck with 52 cards.
     * If $factory is true or null, uses default CardFactory.
     *
     * @param CardFactory|bool|null $factory optional factory to create cards or a boolean to use default
     */
    public function __construct(CardFactory|bool|null $factory = null)
    {
        if (true === $factory || null === $factory) {
            $factory = new CardFactory();
        }

        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                // Create card with CardGraphic regardless of factory param?
                // You might want to use $factory->createCard($suit, $value) instead.
                $this->cards[] = new CardGraphic($suit, $value);
            }
        }
    }

    /**
     * Shuffles the deck of cards randomly.
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Draws a number of cards from the top of the deck.
     *
     * @param int $number number of cards to draw (default 1)
     *
     * @return Card[] array of drawn cards
     */
    public function draw(int $number = 1): array
    {
        return array_splice($this->cards, 0, $number);
    }

    /**
     * Returns all remaining cards in the deck.
     *
     * @return Card[] array of cards in the deck
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Returns the count of remaining cards in the deck.
     *
     * @return int number of cards left
     */
    public function count(): int
    {
        return count($this->cards);
    }
}
