<?php

// src/Game/Game21.php

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
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();

        $this->player = new Player();
        $this->bank = new Bank();
    }

    public function startRound(): void
    {
        if (0 === $this->player->getScore()) {
            $this->player->drawCard($this->deck);
        }
    }

    public function playerDraw(): void
    {
        $this->player->drawCard($this->deck);
        if ($this->player->getScore() > 21) {
            $this->status = 'bank_wins';
        }
    }

    public function playerStay(): void
    {
        while ($this->bank->getScore() < 17) {
            $this->bank->drawCard($this->deck);
        }

        $this->determineWinner();
    }

    private function determineWinner(): void
    {
        $playerScore = $this->player->getScore();
        $bankScore = $this->bank->getScore();

        if ($bankScore > 21 || $playerScore > $bankScore) {
            $this->status = 'player_wins';
        } else {
            $this->status = 'bank_wins';
        }
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
