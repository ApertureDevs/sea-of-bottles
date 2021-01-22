<?php

namespace App\Presentation\Api\Controller;

use App\Core\SharedKernel\Application\CommandInterface;
use App\Core\SharedKernel\Application\CommandResponseInterface;
use App\Presentation\Api\CommandGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\SerializerInterface;

abstract class CommandController extends AbstractController
{
    protected MessageBusInterface $commandBus;
    protected SerializerInterface $serializer;
    protected CommandGenerator $commandGenerator;

    public function __construct(MessageBusInterface $commandBus, SerializerInterface $serializer, CommandGenerator $commandGenerator)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
        $this->commandGenerator = $commandGenerator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->handle($request);

        return new JsonResponse($this->serializer->serialize($result, 'json'), $this->getSuccessfulHttpCode(), [], true);
    }

    protected function dispatchCommand(CommandInterface $command): CommandResponseInterface
    {
        $envelope = $this->commandBus->dispatch($command);
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \RuntimeException(sprintf('Command bus return any handled stamp. Is "%s" handler missing?', $this->getCommandClass()));
        }

        $result = $stamp->getResult();

        if (!$result instanceof CommandResponseInterface) {
            throw new \RuntimeException('Command handler should only return instance of CommandResponseInterface.');
        }

        return $result;
    }

    protected function handle(Request $request): CommandResponseInterface
    {
        return $this->dispatchCommand($this->commandGenerator->generate($request, $this->getCommandClass()));
    }

    abstract protected function getCommandClass(): string;

    abstract protected function getSuccessfulHttpCode(): int;
}
