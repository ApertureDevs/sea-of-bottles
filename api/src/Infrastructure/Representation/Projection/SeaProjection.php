<?php

namespace App\Infrastructure\Representation\Projection;

use App\Core\Component\Message\Domain\Model\Sea;
use App\Core\Component\Message\Port\SeaProjectionInterface;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;

class SeaProjection implements SeaProjectionInterface
{
    private BottleRepository $bottleRepository;
    private SailorRepository $sailorRepository;

    public function __construct(BottleRepository $bottleRepository, SailorRepository $sailorRepository)
    {
        $this->bottleRepository = $bottleRepository;
        $this->sailorRepository = $sailorRepository;
    }

    public function getSea(): Sea
    {
        return Sea::create(
            $this->bottleRepository->getRemainingBottlesCount(),
            $this->bottleRepository->getDeliveredBottlesCount(),
            $this->sailorRepository->getSailorsCount()
        );
    }
}
