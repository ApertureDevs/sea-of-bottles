<?php

namespace App\Core\Component\Message\Application\ShowSea;

use App\Core\Component\Message\Port\SeaProjectorInterface;
use App\Core\SharedKernel\Application\QueryHandlerInterface;

class ShowSeaHandler implements QueryHandlerInterface
{
    private SeaProjectorInterface $seaProjector;

    public function __construct(SeaProjectorInterface $seaProjector)
    {
        $this->seaProjector = $seaProjector;
    }

    public function __invoke(ShowSeaQuery $query): ShowSeaResponse
    {
        $sea = $this->seaProjector->getSea();

        return ShowSeaResponse::createFromSea($sea);
    }
}
