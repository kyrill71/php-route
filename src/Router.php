<?php
declare(strict_types=1);

namespace Kyrill\PhpRoute;

use Kyrill\PhpRoute\Exeptions\InvalidMiddlewareInterfaceException;

class Router
{
    public array $routes;

    public function addRoute(string $method, string $route, array|callable $action, array $middleware = []): self
    {
        $this->routes[$this->generateRouteName($method, $route)] = new Route($method, $route, $action, $middleware);
        return $this;
    }

    public function resolveRoute(): bool
    {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $matches = [];
        $url = null;
        foreach ($this->routes as $url) {
            if ($url->method === $method && preg_match($url->path, $route, $matches)) {
                break;
            }
        }
        if (empty($matches)) {
            return false;
        }
        if ($url->getMiddlewares() !== []) {
            $this->handleMiddlewares($url);
        }
        if (is_array($url->getAction())) {
            $this->handleArray($url, $matches);

            return true;
        }

        if (is_callable($url->getAction())) {
            $this->handleCallable($url);

            return true;
        }



        return false;
    }

    /**
     * @throws InvalidMiddlewareInterfaceException
     */
    private function handleMiddlewares(Route $route): void
    {
        foreach ($route->getMiddlewares() as $middleware) {
            $middlewareObject = new $middleware();
            if (!$middlewareObject instanceof MiddlewareInterface) {
                throw new InvalidMiddlewareInterfaceException("Middleware must implement MiddlewareInterface");
            }
            $middlewareObject->handle();
        }
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
        if(is_string($controllerName)) {
            if (!class_exists($controllerName)) {
                throw new \RuntimeException("Controller $controllerName does not exist");
            }

            if (!method_exists($controllerName, $methodName)) {
                throw new \RuntimeException("Method $methodName does not exist in $controllerName");
            }
        }

        $parameterNames = $matches ?? [];
        $namedKeys = [];

        foreach ($parameterNames as $key => $value) {
            if (is_string($key)) {
                $namedKeys[$key] = $value;
            }
        }
        if (is_string($controllerName)) {
            $controller = new $controllerName();
        }
        else {
            $controller = $controllerName;
        }

        if (!empty($namedKeys)) {
            $parameters = [];

            foreach ($namedKeys as $paramName => $value) {

                if ($value !== null) {
                    $parameters[] = $value;

                } else {
                    throw new \RuntimeException("Invalid parameter: $paramName");
                }
            }

            call_user_func_array([$controllerName, $methodName], $parameters);
        } else {
            $controller->$methodName();
        }
    }
}
