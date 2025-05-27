<?php

namespace App\Tests\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game21;
use App\Game\Player;
use App\Game\Bank;

class Game21Test extends TestCase
{
    public function testGameInitializesCorrectly(): void
    {
        $game = new Game21();

        $this->assertInstanceOf(Player::class, $game->getPlayer());
        $this->assertInstanceOf(Bank::class, $game->getBank());
        $this->assertEquals('ongoing', $game->getStatus());
    }

    public function testStartRoundDealsOneCardToPlayer(): void
    {
        $game = new Game21();
        $game->startRound();

        $this->assertGreaterThan(0, $game->getPlayer()->getScore());
        $this->assertEquals('ongoing', $game->getStatus());
    }

    public function testPlayerDrawsCardAndMayLose(): void
    {
        $game = new Game21();

        for ($i = 0; $i < 5; $i++) {
            $game->playerDraw();
            if ($game->getPlayer()->getScore() > 21) {
                break;
            }
        }

        if ($game->getPlayer()->getScore() > 21) {
            $this->assertEquals('bank_wins', $game->getStatus());
        } else {
            $this->assertEquals('ongoing', $game->getStatus());
        }
    }

    public function testPlayerStayLeadsToBankDrawAndGameEnds(): void
    {
        $game = new Game21();
        $game->startRound();
        $game->playerStay();

        $status = $game->getStatus();
        $this->assertContains($status, ['player_wins', 'bank_wins']);
    }

    public function testPlayerWinsAgainstBank(): void
    {
        $game = new Game21();

        $game->getPlayer()->drawCardFromValue(10);
        $game->getPlayer()->drawCardFromValue(9);

        $game->getBank()->drawCardFromValue(10);
        $game->getBank()->drawCardFromValue(7);

        $ref = new \ReflectionClass($game);
        $method = $ref->getMethod('determineWinner');
        $method->setAccessible(true);
        $method->invoke($game);

        $this->assertEquals('player_wins', $game->getStatus());
    }

    public function testBankWinsWithHigherScore(): void
    {
        $game = new Game21();

        $game->getPlayer()->drawCardFromValue(10);
        $game->getPlayer()->drawCardFromValue(5);

        $game->getBank()->drawCardFromValue(10);
        $game->getBank()->drawCardFromValue(7);

        $ref = new \ReflectionClass($game);
        $method = $ref->getMethod('determineWinner');
        $method->setAccessible(true);
        $method->invoke($game);

        $this->assertEquals('bank_wins', $game->getStatus());
    }
}
