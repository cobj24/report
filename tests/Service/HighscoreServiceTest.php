<?php

namespace App\Tests\Service;

use App\Service\HighscoreService;
use PHPUnit\Framework\TestCase;

class HighscoreServiceTest extends TestCase
{
    private string $tempDir;
    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/highscore_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);
        $this->tempFile = $this->tempDir . '/var/highscores.json';

        mkdir(dirname($this->tempFile), 0777, true); // säkerställ att "var/" finns
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        if (file_exists(dirname($this->tempFile))) {
            rmdir(dirname($this->tempFile));
        }
        if (file_exists($this->tempDir)) {
            rmdir($this->tempDir);
        }
    }

    public function testGetHighscoresWhenFileDoesNotExist(): void
    {
        $service = new HighscoreService($this->tempDir);
        $this->assertSame([], $service->getHighscores());
    }

    public function testAddScoreAndGetHighscores(): void
    {
        $service = new HighscoreService($this->tempDir);
        $service->addScore('Alice', 100);
        $service->addScore('Bob', 200);

        $highscores = $service->getHighscores();

        $this->assertCount(2, $highscores);
        $this->assertSame('Bob', $highscores[0]['name']);
        $this->assertSame(200, $highscores[0]['score']);
        $this->assertSame('Alice', $highscores[1]['name']);
    }

    public function testOnlyTop10AreKept(): void
    {
        $service = new HighscoreService($this->tempDir);

        for ($i = 1; $i <= 12; $i++) {
            $service->addScore("Player{$i}", $i * 10);
        }

        $highscores = $service->getHighscores();

        $this->assertCount(10, $highscores);
        $this->assertSame('Player12', $highscores[0]['name']);
        $this->assertSame(120, $highscores[0]['score']);
    }
}
