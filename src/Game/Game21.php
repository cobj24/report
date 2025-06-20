<?php

namespace App\Game;

use App\Card\DeckOfCards;

/**
 * Klass som hanterar logiken för spelet 21 (Blackjack).
 */
class Game21
{
    private DeckOfCards $deck;
    private Player $player;
    private Bank $bank;
    private string $status = 'ongoing';

    /**
     * Konstruktor som initierar spelet.
     */
    public function __construct()
    {
        $this->initGame();
    }

    /**
     * Initierar spelet genom att skapa och blanda kortleken samt skapa spelare och bank.
     */
    private function initGame(): void
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();

        $this->player = new Player();
        $this->bank = new Bank();
    }

    /**
     * Startar en runda och låter spelaren dra sitt första kort om det är första turen.
     */
    public function startRound(): void
    {
        if ($this->isFirstTurn()) {
            $this->player->drawCard($this->deck);
        }
    }

    /**
     * Kontrollerar om det är första turen för spelaren.
     *
     * @return bool true om spelarens poäng är 0, annars false
     */
    private function isFirstTurn(): bool
    {
        return 0 === $this->player->getScore();
    }

    /**
     * Låter spelaren dra ett kort och uppdaterar status om spelaren blir "busted".
     */
    public function playerDraw(): void
    {
        $this->player->drawCard($this->deck);

        if ($this->isPlayerBusted()) {
            $this->status = 'bank_wins';
        }
    }

    /**
     * Kontrollerar om spelaren har överskridit 21 poäng.
     *
     * @return bool true om spelarens poäng är över 21, annars false
     */
    private function isPlayerBusted(): bool
    {
        return $this->player->getScore() > 21;
    }

    /**
     * Spelaren väljer att stanna. Banken drar kort tills poängen är minst 17 och vinnaren bestäms.
     */
    public function playerStay(): void
    {
        $this->bankDrawUntilLimit();
        $this->determineWinner();
    }

    /**
     * Banken drar kort tills poängen är minst 17.
     */
    private function bankDrawUntilLimit(): void
    {
        while ($this->bank->getScore() < 17) {
            $this->bank->drawCard($this->deck);
        }
    }

    /**
     * Bestämmer vinnaren baserat på poäng och uppdaterar status.
     */
    private function determineWinner(): void
    {
        $playerScore = $this->player->getScore();
        $bankScore = $this->bank->getScore();

        if ($this->bankIsBusted() || $playerScore > $bankScore) {
            $this->status = 'player_wins';
        } else {
            $this->status = 'bank_wins';
        }
    }

    /**
     * Kontrollerar om banken har överskridit 21 poäng.
     *
     * @return bool true om bankens poäng är över 21, annars false
     */
    private function bankIsBusted(): bool
    {
        return $this->bank->getScore() > 21;
    }

    /**
     * Hämtar spelarobjektet.
     *
     * @return Player spelaren
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Hämtar bankobjektet.
     *
     * @return Bank banken
     */
    public function getBank(): Bank
    {
        return $this->bank;
    }

    /**
     * Hämtar aktuell status för spelet.
     *
     * @return string Status, t.ex. 'ongoing', 'player_wins', eller 'bank_wins'.
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
