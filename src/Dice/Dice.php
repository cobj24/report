<?php

namespace App\Dice;

/**
 * Klass som representerar en sexsidig tärning.
 */
class Dice
{
    /**
     * Tärningens aktuella värde (1-6).
     */
    protected int $value;

    /**
     * Konstruktor. Slumpar ett startvärde för tärningen.
     */
    public function __construct()
    {
        $this->value = random_int(1, 6);
    }

    /**
     * Kastar (slumpar) tärningen och returnerar resultatet.
     *
     * @return int det nya tärningsvärdet mellan 1 och 6
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);

        return $this->value;
    }

    /**
     * Returnerar tärningens värde som en sträng, t.ex. "[3]".
     *
     * @return string tärningens värde som sträng
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
