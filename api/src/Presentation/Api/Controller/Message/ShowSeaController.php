<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\ShowSea\ShowSeaQuery;
use App\Presentation\Api\Controller\QueryController;

final class ShowSeaController extends QueryController
{
    protected function getQueryClass(): string
    {
        return ShowSeaQuery::class;
    }
}
