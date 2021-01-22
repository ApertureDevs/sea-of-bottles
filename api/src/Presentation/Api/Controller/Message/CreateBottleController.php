<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleCommand;
use App\Presentation\Api\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateBottleController extends CommandController
{
    protected function getCommandClass(): string
    {
        return CreateBottleCommand::class;
    }

    protected function getSuccessfulHttpCode(): int
    {
        return JsonResponse::HTTP_CREATED;
    }
}
