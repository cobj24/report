<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\PokerSquareGame;
use App\Card\Card;

class PokerSquareGameTest extends TestCase
{
    public function testInitialState(): void
    {
        $game = new PokerSquareGame();

        $this->assertIsArray($game->getGrid());
        $this->assertInstanceOf(Card::class, $game->getCurrentCard());
        $this->assertFalse($game->isGameOver());
    }

    public function testPlaceCard(): void
    {
        $game = new PokerSquareGame();
        $pos = '0-0';
        $currentCard = $game->getCurrentCard();

        $game->placeCard($pos);
        $grid = $game->getGrid();

        $this->assertArrayHasKey($pos, $grid);
        $this->assertSame($currentCard, $grid[$pos]);
    }

    public function testGetTotalScoreReturnsInt(): void
    {
        $game = new PokerSquareGame();

        for ($i = 0; $i < 5; $i++) {
            $game->placeCard("0-$i");
        }

        $totalScore = $game->getTotalScore();

        $this->assertIsInt($totalScore);
        $this->assertGreaterThanOrEqual(0, $totalScore);
    }

    public function testGetSuggestedMoveReturnsValidPosition(): void
    {
        $game = new PokerSquareGame();

        $suggested = $game->getSuggestedMove();

        $this->assertNotNull($suggested);
        $this->assertMatchesRegularExpression('/^[0-4]-[0-4]$/', $suggested);
    }

    public function testPlaceCardDoesNotOverwrite(): void
    {
        $game = new PokerSquareGame();
        $pos = '0-0';
        $firstCard = $game->getCurrentCard();

        $game->placeCard($pos);
        $game->placeCard($pos);

        $grid = $game->getGrid();
        $this->assertSame($firstCard, $grid[$pos]);
    }

    public function testGetGridScoresReturnsExpectedStructure(): void
    {
        $game = new PokerSquareGame();

        for ($i = 0; $i < 5; $i++) {
            $game->placeCard("0-$i");
        }

        $scores = $game->getGridScores();

        $this->assertArrayHasKey('rows', $scores);
        $this->assertArrayHasKey('cols', $scores);
        $this->assertCount(5, $scores['rows']);
        $this->assertCount(5, $scores['cols']);
    }

    public function testIsGameOverWhenGridFull(): void
    {
        $game = new PokerSquareGame();

        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++) {
                $game->placeCard("$row-$col");
            }
        }

        $this->assertTrue($game->isGameOver());
    }

    public function testGameOverWhenGridFull(): void
    {
        $game = new PokerSquareGame();
        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++) {
                $pos = "$row-$col";
                $game->placeCard($pos);
            }
        }

        $this->assertTrue($game->isGameOver());
    }
}
