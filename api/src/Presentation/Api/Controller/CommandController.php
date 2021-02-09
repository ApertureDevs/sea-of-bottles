<?php

namespace App\Presentation\Api\Controller;

use App\Core\SharedKernel\Application\CommandInterface;
use App\Core\SharedKernel\Application\CommandResponseInterface;
use App\Presentation\Api\CommandGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class CommandController extends AbstractController
{
    protected MessageBusInterface $commandBus;
    protected SerializerInterface $serializer;
    protected CommandGenerator $commandGenerator;
    protected ValidatorInterface $validator;

    public function __construct(
        MessageBusInterface $commandBus,
        SerializerInterface $serializer,
        CommandGenerator $commandGenerator,
        ValidatorInterface $validator
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
        $this->commandGenerator = $commandGenerator;
        $this->validator = $validator;
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
        $command = $this->commandGenerator->generate($request, $this->getCommandClass());
        $this->validateCommand($command);

        return $this->dispatchCommand($command);
    }

    protected function validateCommand(CommandInterface $command): void
    {
        $constraintViolationList = $this->validator->validate($command);

        if ($constraintViolationList->count() > 0) {
            $errorMessages = [];

            /** @var ConstraintViolation $violation */
            foreach ($constraintViolationList as $violation) {
                $errorMessages[] = $violation->getPropertyPath().' : '.$violation->getMessage();
            }

            throw new BadRequestException(implode("\n", $errorMessages));
        }
    }

    abstract protected function getCommandClass(): string;

    abstract protected function getSuccessfulHttpCode(): int;
}
