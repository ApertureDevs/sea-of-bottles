<?php

namespace App\Core\Component\Message\Domain\Model;

class Sea
{
    private int $bottlesRemaining;

    private int $bottlesRecovered;

    private int $bottlesTotal;

    private int $sailorsTotal;

    private function __construct(int $bottlesRemaining, int $bottlesRecovered, int $sailorsTotal)
    {
        $this->bottlesRemaining = $bottlesRemaining;
        $this->bottlesRecovered = $bottlesRecovered;
        $this->bottlesTotal = $bottlesRecovered + $bottlesRemaining;
        $this->sailorsTotal = $sailorsTotal;
    }

    public static function create(int $bottlesRemaining, int $bottlesRecovered, int $sailorsTotal): Sea
    {
        return new self($bottlesRemaining, $bottlesRecovered, $sailorsTotal);
    }

    public function getBottlesRemaining(): int
    {
        return $this->bottlesRemaining;
    }

    public function getBottlesRecovered(): int
    {
        return $this->bottlesRecovered;
    }

    public function getBottlesTotal(): int
    {
        return $this->bottlesTotal;
    }

    public function getSailorsTotal(): int
    {
        return $this->sailorsTotal;
    }
}
