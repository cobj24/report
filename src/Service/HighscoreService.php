<?php

namespace App\Service;

/**
 * Tjänst för att hantera highscore-listor.
 * Läser från och skriver till en JSON-fil som lagrar topp 10 resultat.
 */
class HighscoreService
{
    /** @var string Sökvägen till JSON-filen som lagrar highscores */
    private string $file;

    /**
     * Konstruktor.
     *
     * @param string $projectDir Projektets rotkatalog (används för att hitta/highscores.json)
     */
    public function __construct(string $projectDir)
    {
        $this->file = $projectDir.'/var/highscores.json';
    }

    /**
     * Returnerar en lista med highscores.
     *
     * @return array En array med highscore-poster, varje post innehåller 'name' och 'score'
     */
    public function getHighscores(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    /**
     * Lägger till en ny score och uppdaterar filen.
     * Endast de 10 bästa resultaten sparas.
     *
     * @param string $name  Namn på spelaren
     * @param int    $score Poäng
     */
    public function addScore(string $name, int $score): void
    {
        $scores = $this->getHighscores();
        $scores[] = ['name' => $name, 'score' => $score];
        usort($scores, fn ($a, $b) => $b['score'] <=> $a['score']);
        $scores = array_slice($scores, 0, 10);

        file_put_contents($this->file, json_encode($scores, JSON_PRETTY_PRINT));
    }
}
