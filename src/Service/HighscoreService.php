<?php

namespace App\Service;

class HighscoreService
{
    private string $file;

    public function __construct(string $projectDir)
    {
        $this->file = $projectDir . '/var/highscores.json';
    }

    public function getHighscores(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function addScore(string $name, int $score): void
    {
        $scores = $this->getHighscores();
        $scores[] = ['name' => $name, 'score' => $score];
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
        $scores = array_slice($scores, 0, 10);

        file_put_contents($this->file, json_encode($scores, JSON_PRETTY_PRINT));
    }
}
