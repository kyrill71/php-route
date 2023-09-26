<?php

declare(strict_types=1);

namespace Kyrill\PhpRoute;

class Route
{
    public string $method;

    public string $path;

    private $action;

    public function __construct(string $method, string $path, array|callable $action)
    {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
    }

    public function getAction(): array|callable
    {
        return $this->action;
    }
}
