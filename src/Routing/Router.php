<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;

use adrianschubek\Routing\Exceptions\NamedRouteNotFound;

class Router
{
    protected array $routes = [];
    protected array $middlewareGroups = [];
    protected string $subdir = "";
    protected $errorRoute;

    public function get(string $routePath, $controller)
    {
        $route = new Route("GET", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function post(string $routePath, $controller)
    {
        $route = new Route("POST", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function put(string $routePath, $controller)
    {
        $route = new Route("PUT", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function group(string $name, array $middleware)
    {
        $this->middlewareGroups[$name] = $middleware;
    }

    public function delete(string $routePath, $controller)
    {
        $route = new Route("DELETE", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function patch(string $routePath, $controller)
    {
        $route = new Route("PATCH", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function options(string $routePath, $controller)
    {
        $route = new Route("OPTIONS", $routePath, $controller);
        $this->routes[] = $route;
        return $route;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function dispatch($method = null, $uri = null)
    {
        $method = $method ?? $_SERVER['REQUEST_METHOD'];
        $uri = $uri ?? $_SERVER['REQUEST_URI'];
        $uri = str_replace($this->subdir, "", $uri);
        $found = null;
        $parameterMatches = [];

        foreach ($this->routes as $route) {
            if ($route->getMethod() !== $method || ($route->getRoute() === "\/" && $uri !== "/")) {
                continue;
            }
            if (preg_match_all("/" . $route->getRoute() . "$/", $uri, $parameterMatches)) {
                $found = $route;
                break;
            }
        }
        array_shift($parameterMatches);

        return $this->resolve()($found, $parameterMatches);
    }

    /**
     * => function(Route $route, array $params)
     * @return callable
     */
    public function resolve(): callable
    {
        return function (Route $route, array $params) {
            $route->getCallback()(...$this->flatten($params));
        };
    }

    private function flatten(array $array, float $depth = INF): array
    {
        $result = [];
        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : $this->flatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    public function route(string $name, array $values = []): string
    {
        $url = $this->routes[array_search($name, array_column($this->routes, "name"))]->getStringRoute();
        foreach ($values as $key => $value) {
            $url = str_replace("[" . $key . "]", $value, $url);
        }
        if ($url === null) {
            throw new NamedRouteNotFound($name);
        }
        return $url;
    }

    public function error(callable $callback)
    {
        $this->errorRoute = $callback;
        return $this;
    }

    public function setSubdir(string $subdir)
    {
        $this->subdir = $subdir;
        return $this;
    }
}