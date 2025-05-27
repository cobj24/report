<?php

namespace App\Game;

use App\Card\DeckOfCards;

class Game21
{
    private DeckOfCards $deck;
    private Player $player;
    private Bank $bank;
    private string $status = 'ongoing';

    public function __construct()
    {
        $this->initGame();
    }

    private function initGame(): void
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();

        $this->player = new Player();
        $this->bank = new Bank();
    }

    public function startRound(): void
    {
        if ($this->isFirstTurn()) {
            $this->player->drawCard($this->deck);
        }
    }

    private function isFirstTurn(): bool
    {
        return 0 === $this->player->getScore();
    }

    public function playerDraw(): void
    {
        $this->player->drawCard($this->deck);

        if ($this->isPlayerBusted()) {
            $this->status = 'bank_wins';
        }
    }

    private function isPlayerBusted(): bool
    {
        return $this->player->getScore() > 21;
    }

    public function playerStay(): void
    {
        $this->bankDrawUntilLimit();
        $this->determineWinner();
    }

    private function bankDrawUntilLimit(): void
    {
        while ($this->bank->getScore() < 17) {
            $this->bank->drawCard($this->deck);
        }
    }

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

    private function bankIsBusted(): bool
    {
        return $this->bank->getScore() > 21;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
