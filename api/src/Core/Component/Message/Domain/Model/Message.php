<?php

namespace App\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\InvalidMessageException;

class Message
{
    private const CONTENT_LIMIT = 5000;
    private string $content;

    private function __construct(string $content)
    {
        $content = trim($content, ' ');

        if (empty($content)) {
            throw InvalidMessageException::createEmptyContentException();
        }

        if (strlen($content) > self::CONTENT_LIMIT) {
            throw InvalidMessageException::createExceededContentLengthLimitException(self::CONTENT_LIMIT);
        }

        $this->content = $content;
    }

    public static function create(string $content): Message
    {
        return new self($content);
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
