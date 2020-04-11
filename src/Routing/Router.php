<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;

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

        return call_user_func($found->getCallback(), $parameterMatches);
        return var_dump([$method, $uri, $found, $parameterMatches]);

//        if($found) {
//            return $this->
//        }

        if (!$found) {
            $this->call($this->errorRoute, $parameterMatches);
//            die("Router: Not Found");
        } else {
//            $this->call($route->getController(), $parameterMatches);
//            die(join(", ", [$method, $uri, $found, $parameterMatches]));
        }
    }

    public function route(string $name, array $values = []): string
    {
        $url = $this->routes[array_search($name, array_column($this->routes, "name"))]->getStringRoute();
        foreach ($values as $key => $value) {
            $url = str_replace("[" . $key . "]", $value, $url);
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