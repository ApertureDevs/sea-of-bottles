<?php

namespace App\Core\Component\Message\Application\ShowSea;

use App\Core\Component\Message\Port\SeaProjectionInterface;
use App\Core\SharedKernel\Application\QueryHandlerInterface;

class ShowSeaHandler implements QueryHandlerInterface
{
    private SeaProjectionInterface $seaProjection;

    public function __construct(SeaProjectionInterface $seaProjection)
    {
        $this->seaProjection = $seaProjection;
    }

    public function __invoke(ShowSeaQuery $query): ShowSeaResponse
    {
        $sea = $this->seaProjection->getSea();

        return ShowSeaResponse::createFromSea($sea);
    }
}
