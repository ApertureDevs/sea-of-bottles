<?php

namespace App\Presentation\Api\Controller;

use App\Core\SharedKernel\Application\QueryInterface;
use App\Core\SharedKernel\Application\QueryResponseInterface;
use App\Presentation\Api\QueryGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class QueryController extends AbstractController
{
    protected MessageBusInterface $queryBus;
    protected SerializerInterface $serializer;
    protected QueryGenerator $queryGenerator;
    protected ValidatorInterface $validator;

    public function __construct(
        MessageBusInterface $queryBus,
        SerializerInterface $serializer,
        QueryGenerator $queryGenerator,
        ValidatorInterface $validator
    ) {
        $this->queryBus = $queryBus;
        $this->serializer = $serializer;
        $this->queryGenerator = $queryGenerator;
        $this->validator = $validator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->handle($request);

        return new JsonResponse($this->serializer->serialize($result, 'json'), JsonResponse::HTTP_OK, [], true);
    }

    protected function dispatchQuery(QueryInterface $query): QueryResponseInterface
    {
        $envelope = $this->queryBus->dispatch($query);

        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \RuntimeException(sprintf('Query bus return any handled stamp. Is "%s" handler missing?', $this->getQueryClass()));
        }

        $result = $stamp->getResult();

        if (!$result instanceof QueryResponseInterface) {
            throw new \RuntimeException('Invalid stamp result.');
        }

        return $result;
    }

    protected function handle(Request $request): QueryResponseInterface
    {
        $query = $this->queryGenerator->generate($request, $this->getQueryClass());
        $this->validateQuery($query);

        return $this->dispatchQuery($query);
    }

    protected function validateQuery(QueryInterface $query): void
    {
        $constraintViolationList = $this->validator->validate($query);

        if ($constraintViolationList->count() > 0) {
            $errorMessages = [];

            /** @var ConstraintViolation $violation */
            foreach ($constraintViolationList as $violation) {
                $errorMessages[] = $violation->getPropertyPath().' : '.$violation->getMessage();
            }

            throw new BadRequestException(implode("\n", $errorMessages));
        }
    }

    abstract protected function getQueryClass(): string;
}
