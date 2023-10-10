<?php

declare(strict_types=1);

namespace Kyrill\PhpRoute;

use http\Env\Request;

class Router
{
    private const DEFAULT_ROUTE_PATTERN = '([0-9]+)';

    public array $routes;

    public function addRoute(string $method, string $route, array|callable $action): self
    {
        $this->routes[$this->generateRouteName($method, $route)] = new Route($method, $route, $action);

        return $this;
    }

    public function resolveRoute(): bool
    {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $matches = [];
        $url = null;
        foreach($this->routes as $url) {
            if(preg_match($url->path, $route, $matches)) {
                if($url->method === $method) {
                    break;
                }
                
            }
        }
       if (empty($matches)) {
            return false;
        }

        if (is_callable($url->getAction())) {
            $this->handleCallable($url);

            return true;
        }

        if (is_array($url->getAction())) {
            $this->handleArray($url, $matches);

            return true;
        }

        return false;
    }

    private function handleCallable(Route $route): void
    {
        call_user_func($route->getAction());
    }

    private function generateRouteName(string $method, string $route): string
    {
        return sprintf('%s_%s', $method, $route);
    }

    private function handleArray(Route $route, array $matches): void
    {
        [$controllerName, $methodName] = $route->getAction();
        if (!class_exists($controllerName)) {
            throw new \RuntimeException("Controller $controllerName does not exist");
        }

        if (!method_exists($controllerName, $methodName)) {
            throw new \RuntimeException("Method $methodName does not exist in $controllerName");
        }
        $parameterNames = $matches ?? [];
        $namedKeys = [];

        foreach ($parameterNames as $key => $value) {
            if (is_string($key)) {
                $namedKeys[$key] = $value;
            }
        }

        $controller = new $controllerName();

        if (!empty($namedKeys)) {
            $parameters = [];

            foreach ($namedKeys as $paramName => $pattern) {
                // Use $value from $parameterNames array
                if ($value !== null && preg_match("/^$pattern$/", $value)) {
                    $parameters[] = $value;
                } else {
                    throw new \RuntimeException("Invalid parameter: $paramName");
                }
            }

            call_user_func_array([$controller, $methodName], $parameters);
    } else {
            $controller->$methodName();
        }
    }




}
