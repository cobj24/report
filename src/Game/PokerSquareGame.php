<?php

namespace App\Game;

use App\Card\DeckOfCards;
use App\Card\Card;

class PokerSquareGame
{
    private array $grid = [];
    private DeckOfCards $deck;
    private ?Card $currentCard = null;

    public function __construct()
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();
        $this->drawNextCard();
    }

    public function getGrid(): array
    {
        return $this->grid;
    }

    public function getCurrentCard(): ?Card
    {
        return $this->currentCard;
    }

    public function placeCard(string $position): void
    {
        if (!$this->currentCard || isset($this->grid[$position])) {
            return;
        }

        $this->grid[$position] = $this->currentCard;
        $this->drawNextCard();
    }

    public function drawNextCard(): void
    {
        $drawn = $this->deck->draw(1);
        $this->currentCard = $drawn[0] ?? null;
    }

    public function isGameOver(): bool
    {
        return count($this->grid) >= 25 || $this->currentCard === null;
    }

    public function getGridScores(): array
    {
        $scores = [
            'rows' => [],
            'cols' => []
        ];

        for ($i = 0; $i < 5; $i++) {
            $row = [];
            $col = [];

            for ($j = 0; $j < 5; $j++) {
                $rowKey = $i . '-' . $j;
                $colKey = $j . '-' . $i;

                $row[] = $this->grid[$rowKey] ?? null;
                $col[] = $this->grid[$colKey] ?? null;
            }

            $scores['rows'][$i] = $this->calculateHandScore($row);
            $scores['cols'][$i] = $this->calculateHandScore($col);
        }

        return $scores;
    }

    private function calculateHandScore(array $hand): int
    {
        if (in_array(null, $hand, true)) {
            return 0;
        }

        return random_int(1, 10);
    }

    public function getTotalScore(): int
    {
        $scores = $this->getGridScores();
        return array_sum($scores['rows']) + array_sum($scores['cols']);
    }

    public function getSuggestedMove(): ?string
    {
        if (!$this->currentCard || $this->isGameOver()) {
            return null;
        }

        $bestScore = -1;
        $bestPos = null;

        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++) {
                $pos = "$row-$col";
                if (isset($this->grid[$pos])) {
                    continue;
                }

                $tempGrid = $this->grid;
                $tempGrid[$pos] = $this->currentCard;

                $score = $this->simulateScore($tempGrid);

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestPos = $pos;
                }
            }
        }

        return $bestPos;
    }

    private function simulateScore(array $grid): int
    {
        $score = 0;

        for ($i = 0; $i < 5; $i++) {
            $row = [];
            $col = [];

            for ($j = 0; $j < 5; $j++) {
                $row[] = $grid["$i-$j"] ?? null;
                $col[] = $grid["$j-$i"] ?? null;
            }

            $score += $this->calculateHandScore($row);
            $score += $this->calculateHandScore($col);
        }

        return $score;
    }

}
