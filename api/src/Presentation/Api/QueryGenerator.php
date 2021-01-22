<?php

namespace App\Presentation\Api;

use App\Core\SharedKernel\Application\QueryInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryGenerator
{
    public function generate(Request $request, string $queryClass): QueryInterface
    {
        $query = new $queryClass();

        if ($query instanceof QueryInterface) {
            return $query;
        }

        throw new \InvalidArgumentException(sprintf('queryClass should be an instance of "%s"', QueryInterface::class));
    }
}
