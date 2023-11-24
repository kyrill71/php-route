<?php

declare(strict_types=1);

namespace Kyrill\PhpRoute;

class Route
{
    public string $method;

    public string $path;

    private $action;

    private array $middlewares;

    public function __construct(string $method, string $path, array|callable $action, array $middlewares = [])
    {
        $this->method = $method;
        $this->path = $this->buildPatternUrl($path);
        $this->action = $action;
        $this->middlewares = $middlewares;
    }

    public function getAction(): array|callable
    {
        return $this->action;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    private function buildPatternUrl(string $path): string
    {
        $path = str_replace('/', '\/', $path);
        $matches = [];
        preg_match_all('/{(.+?)}/m', $path, $matches, PREG_SET_ORDER, 0);
        if (empty($matches)) {
            return '/' . $path . "$/";
        }
        foreach ($matches as $match) {
            $identifier = explode(':', $match[1])[0];
            $regex = $this->getRegex($match[1] ?? null);
            $path = str_replace($match[0], '(?<'.$identifier.'>'.$regex. ')', $path);
        }
        return '/' . $path . "$/";
    }

    private function getRegex(?string $match): string
    {
        $parsed = explode(':', $match);
        if ($match === null || count($parsed) === 1) {
            return '([0-9]+)';
        }
        $regex = $parsed[1];
        return $regex;
    }
}
