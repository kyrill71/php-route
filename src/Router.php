<?php
declare(strict_types = 1);

namespace Kyrill\PhpRoute;

class Router
{
    public array $routes;
    public function addRoute(string $method, string $route, array|callable $action): self
    {
        $this->routes[$this->generateRouteName($method, $route)] = new Route($method, $route, $action);
        return $this;
    }
    public function resolveRoute(string $method, string $route): bool
    {
        $routeName = $this->generateRouteName($method, $route);
        if (!array_key_exists($routeName, $this->routes)) {
            return false;
        }

        $route = $this->routes[$this->generateRouteName($method, $route)];

        if (is_callable($route->getAction())) {
            $this->handleCallable($route);
            return true;
        }

        if (is_array($route->getAction())) {
            $this->handleArray($route);
            return true;
        }

        return false;
    }

    private function handleCallable(Route $route): void
    {
        call_user_func($route->getAction());
    }

    private function handleArray(Route $route): void
    {
        [$controllerName, $methodName] = $route->getAction();
        if (!class_exists($controllerName)) {
            throw new \RuntimeException("Controller $controllerName does not exist");
            return;
        }

        if (!method_exists($controllerName, $methodName)) {
            throw new \RuntimeException("Method $methodName does not exist in $controllerName");
            return;
        }

        $controller = new $controllerName();
        $controller->$methodName();
    }

    private function generateRouteName(string $method, string $route): string
    {
        return sprintf("%s_%s", $method, $route);
    }
}