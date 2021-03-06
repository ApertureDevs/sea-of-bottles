<?php

namespace App\Presentation\Api\Controller\Message;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleCommand;
use App\Core\SharedKernel\Application\CommandResponseInterface;
use App\Presentation\Api\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CreateBottleController extends CommandController
{
    protected function getCommandClass(): string
    {
        return CreateBottleCommand::class;
    }

    protected function handle(Request $request): CommandResponseInterface
    {
        $command = $this->commandGenerator->generate($request, $this->getCommandClass());

        if (!$command instanceof CreateBottleCommand) {
            throw new \RuntimeException(sprintf('Command should be instance of "%s".', CreateBottleCommand::class));
        }

        $createIp = $request->getClientIp();

        if (null === $createIp) {
            throw new \RuntimeException('Client Ip cannot be null.');
        }

        $command->createIp = $createIp;
        $this->validateCommand($command);

        return $this->dispatchCommand($command);
    }

    protected function getSuccessfulHttpCode(): int
    {
        return JsonResponse::HTTP_CREATED;
    }
}
