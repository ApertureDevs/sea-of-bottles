<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Presentation\Api\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DeleteSailorController extends CommandController
{
    protected function getCommandClass(): string
    {
        return DeleteSailorCommand::class;
    }

    protected function getSuccessfulHttpCode(): int
    {
        return JsonResponse::HTTP_OK;
    }
}
