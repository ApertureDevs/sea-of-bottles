<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorCommand;
use App\Presentation\Api\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateSailorController extends CommandController
{
    protected function getCommandClass(): string
    {
        return CreateSailorCommand::class;
    }

    protected function getSuccessfulHttpCode(): int
    {
        return JsonResponse::HTTP_CREATED;
    }
}
