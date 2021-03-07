<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Core\SharedKernel\Application\CommandResponseInterface;
use App\Presentation\Api\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class DeleteSailorController extends CommandController
{
    protected function getCommandClass(): string
    {
        return DeleteSailorCommand::class;
    }

    protected function handle(Request $request): CommandResponseInterface
    {
        $command = $this->commandGenerator->generate($request, $this->getCommandClass());

        if (!$command instanceof DeleteSailorCommand) {
            throw new \RuntimeException(sprintf('Command should be instance of "%s".', DeleteSailorCommand::class));
        }

        $deleteIp = $request->getClientIp();

        if (null === $deleteIp) {
            throw new \RuntimeException('Client Ip cannot be null.');
        }

        $command->deleteIp = $deleteIp;
        $this->validateCommand($command);

        return $this->dispatchCommand($command);
    }

    protected function getSuccessfulHttpCode(): int
    {
        return JsonResponse::HTTP_OK;
    }
}
