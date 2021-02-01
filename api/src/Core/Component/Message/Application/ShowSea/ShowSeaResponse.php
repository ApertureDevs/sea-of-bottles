<?php

namespace App\Core\Component\Message\Application\ShowSea;

use App\Core\Component\Message\Domain\Model\Sea;
use App\Core\SharedKernel\Application\QueryResponseInterface;

class ShowSeaResponse implements QueryResponseInterface
{
    public int $bottlesRemaining;
    public int $bottlesRecovered;
    public int $bottlesTotal;
    public int $sailorsTotal;

    private function __construct(int $bottlesRemaining, int $bottlesRecovered, int $bottlesTotal, int $sailorsTotal)
    {
        $this->bottlesRemaining = $bottlesRemaining;
        $this->bottlesRecovered = $bottlesRecovered;
        $this->bottlesTotal = $bottlesTotal;
        $this->sailorsTotal = $sailorsTotal;
    }

    public static function createFromSea(Sea $sea): ShowSeaResponse
    {
        return new self(
            $sea->getBottlesRemaining(),
            $sea->getBottlesRecovered(),
            $sea->getBottlesTotal(),
            $sea->getSailorsTotal(),
        );
    }
}
