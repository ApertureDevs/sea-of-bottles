<?php

namespace App\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionListener
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if (!$throwable instanceof HandlerFailedException) {
            $error = Error::createFromThrowable($throwable);
            $response = new JsonResponse($this->serializer->serialize($error, 'json'), $error->getStatus(), [], true);
            $event->setResponse($response);

            return;
        }

        $exception = $throwable->getPrevious();

        if (null === $exception) {
            throw new \RuntimeException(sprintf('Instance of "%s" should contain previous exception.', HandlerFailedException::class));
        }

        $error = Error::createFromThrowable($exception);
        $response = new JsonResponse($this->serializer->serialize($error, 'json'), $error->getStatus(), [], true);
        $event->setResponse($response);
    }
}
