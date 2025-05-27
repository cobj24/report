<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * Representerar en spelare i spelet 21.
 * Hanterar spelarens hand och beräknar poäng.
 */
class Player
{
    /** @var CardHand Spelarens hand med kort */
    protected CardHand $hand;

    /**
     * Konstruktor som skapar en tom hand.
     */
    public function __construct()
    {
        $this->hand = new CardHand();
    }

    /**
     * Drar ett kort från given kortlek och lägger till i spelarens hand.
     *
     * @param DeckOfCards $deck Kortleken att dra ifrån
     */
    public function drawCard(DeckOfCards $deck): void
    {
        $cards = $deck->draw();
        foreach ($cards as $card) {
            $this->hand->addCard($card);
        }
    }

    /**
     * Beräknar spelarens aktuella poäng enligt reglerna för 21.
     * Ess räknas som 11 eller 1 beroende på totalpoängen.
     *
     * @return int Spelarens aktuella poäng
     */
    public function getScore(): int
    {
        $score = 0;
        $aces = 0;

        foreach ($this->hand->getCards() as $card) {
            $value = $card->getValue();
            if (is_numeric($value)) {
                $score += (int) $value;
            } elseif (in_array($value, ['J', 'Q', 'K'])) {
                $score += 10;
            } elseif ('A' === $value) {
                ++$aces;
            }
        }

        for ($i = 0; $i < $aces; ++$i) {
            if ($score + 11 <= 21) {
                $score += 11;
            } else {
                ++$score;
            }
        }

        return $score;
    }

    /**
     * Returnerar spelarens hand.
     *
     * @return CardHand Spelarens hand
     */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
     * Lägger till ett specifikt kort i spelarens hand, används vid testning.
     *
     * @param int|string $value Kortets värde, t.ex. 2–10, "J", "Q", "K", "A"
     */
    public function drawCardFromValue(int|string $value): void
    {
        $card = new \App\Card\Card('♥', $value);
        $this->hand->addCard($card);
    }
}
