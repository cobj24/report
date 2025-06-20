<?php

namespace App\Game;

use App\Card\Card;
use App\Card\DeckOfCards;

/**
 * Klassen representerar spelet Poker Square.
 *
 * Spelet går ut på att placera ut kort i en 5x5-rutnätslayout,
 * och poäng ges baserat på pokerhänder som bildas i varje rad och kolumn.
 *
 * Kort dras från en kortlek och placeras på specifika positioner i rutnätet.
 * Klassen hanterar kortdragning, placering, beräkning av poäng och
 * förslag på nästa bästa drag.
 */
class PokerSquareGame
{
    /**
     * Rutnätet som lagrar placerade kort, indexerat som "rad-kolumn" (t.ex. "0-0").
     *
     * @var array<string, Card|null>
     */
    private array $grid = [];

    /**
     * Kortleken som används i spelet.
     */
    private DeckOfCards $deck;

    /**
     * Det aktuella kortet som ska placeras.
     */
    private ?Card $currentCard = null;

    /**
     * Initierar ett nytt spel med en ny kortlek och drar första kortet.
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards(true);
        $this->deck->shuffle();
        $this->drawNextCard();
    }

    /**
     * Hämtar den aktuella spelrutnätsmatrisen med placerade kort.
     *
     * @return array<string, Card|null> Rutnätet med kortpositioner
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    /**
     * Hämtar det aktuella kortet som ska placeras.
     *
     * @return Card|null Det aktuella kortet, eller null om inga kort finns kvar
     */
    public function getCurrentCard(): ?Card
    {
        return $this->currentCard;
    }

    /**
     * Placerar det aktuella kortet på angiven position i rutnätet.
     * Ignorerar om kortet redan är placerat eller om positionen är upptagen.
     *
     * @param string $position Position i formatet "rad-kolumn", t.ex. "2-3"
     */
    public function placeCard(string $position): void
    {
        if (!$this->currentCard || isset($this->grid[$position])) {
            return;
        }

        $this->grid[$position] = $this->currentCard;
        $this->drawNextCard();
    }

    /**
     * Drar nästa kort från kortleken och uppdaterar det aktuella kortet.
     */
    public function drawNextCard(): void
    {
        $drawn = $this->deck->draw(1);
        $this->currentCard = $drawn[0] ?? null;
    }

    /**
     * Kontrollerar om spelet är slut (rutnätet är fullt eller inga kort kvar).
     *
     * @return bool True om spelet är slut, annars false
     */
    public function isGameOver(): bool
    {
        return count($this->grid) >= 25 || null === $this->currentCard;
    }

    /**
     * Beräknar poängen för varje rad och kolumn i rutnätet.
     * Använder interna regler för poängberäkning av pokerhänder.
     *
     * @return array<string, array<int, int>> Associativ array med 'rows' och 'cols' poäng
     */
    public function getGridScores(): array
    {
        $scores = [
            'rows' => [],
            'cols' => [],
        ];

        for ($i = 0; $i < 5; ++$i) {
            $row = [];
            $col = [];

            for ($j = 0; $j < 5; ++$j) {
                $rowKey = $i.'-'.$j;
                $colKey = $j.'-'.$i;

                $row[] = $this->grid[$rowKey] ?? null;
                $col[] = $this->grid[$colKey] ?? null;
            }

            $scores['rows'][$i] = $this->calculateHandScore($row);
            $scores['cols'][$i] = $this->calculateHandScore($col);
        }

        return $scores;
    }

    /**
     * Beräknar poängen för en given hand med 5 kort.
     * Returnerar 0 om handen är ofullständig.
     * OBS: För tillfället returnerar metoden ett slumpmässigt värde.
     *
     * @param array<int, Card|null> $hand En rad eller kolumn med 5 kort
     *
     * @return int Poängen för handen
     */
    private function calculateHandScore(array $hand): int
    {
        if (in_array(null, $hand, true)) {
            return 0;
        }

        // TODO: Implementera riktig pokerhand-utvärdering istället för slumpmässigt värde
        return random_int(1, 10);
    }

    /**
     * Beräknar den totala poängen för hela rutnätet (rader + kolumner).
     *
     * @return int Total poäng
     */
    public function getTotalScore(): int
    {
        $scores = $this->getGridScores();

        return array_sum($scores['rows']) + array_sum($scores['cols']);
    }

    /**
     * Föreslår den bästa positionen för att placera det aktuella kortet
     * baserat på en simulering som maximerar poängen.
     *
     * @return string|null Position som "rad-kolumn" eller null om inget drag möjligt
     */
    public function getSuggestedMove(): ?string
    {
        if (!$this->currentCard || $this->isGameOver()) {
            return null;
        }

        $bestScore = -1;
        $bestPos = null;

        for ($row = 0; $row < 5; ++$row) {
            for ($col = 0; $col < 5; ++$col) {
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

    /**
     * Simulerar poängen för ett givet rutnät.
     * Används internt för att hitta bästa drag.
     *
     * @param array<string, Card|null> $grid Rutnät med kort
     *
     * @return int Simulerad total poäng
     */
    private function simulateScore(array $grid): int
    {
        $score = 0;

        for ($i = 0; $i < 5; ++$i) {
            $row = [];
            $col = [];

            for ($j = 0; $j < 5; ++$j) {
                $row[] = $grid["$i-$j"] ?? null;
                $col[] = $grid["$j-$i"] ?? null;
            }

            $score += $this->calculateHandScore($row);
            $score += $this->calculateHandScore($col);
        }

        return $score;
    }
}
