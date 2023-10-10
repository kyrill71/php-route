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
        $this->path = $this->buildPatternUrl($path);
        $this->action = $action;
    }

    public function getAction(): array|callable
    {
        return $this->action;
    }

    private function buildPatternUrl(string $path): string
    {
        $path = str_replace('/', '\/', $path);
        preg_match_all('/{(.+?)}/m', $path, $matches, PREG_SET_ORDER, 0);
        foreach ($matches as $match) {
            $identifier = explode(':', $match[1])[0];
            $regex = $this->getRegex($match[1] ?? null);
            $path = str_replace($match[0], '(?<'.$identifier.'>'.$regex. ')', $path);
        }
        return '/' . $path . "$/";
    }

    private function getRegex(?string $match): string
    {
        if ($match === null) {
            return '([0-9]+)';
        }
        $regex = explode(':', $match)[1];
        return $regex;
    }
}
