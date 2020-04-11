<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;


class Route
{
    public $name;

    protected string $method;
    protected string $route;
    protected string $stringRoute;
    protected $callback;
    protected $middleware = [];
    protected $middlewareGroup = [];

    public function __construct(string $method, string $route, callable $controller)
    {
        $this->method = $method;
        $this->route = $this->regex($route);
        $this->stringRoute = $route;
        $this->callback = $controller;
    }

    public function regex(string $route): string
    {
        $str = preg_quote($route, '/');
        $str = str_replace("\]", "]", $str);
        $str = str_replace("/\\", "/", $str);
        return preg_replace("/\[([a-z]+)]/", "([a-z0-9_-]+)", $str);
    }

    public function getStringRoute(): string
    {
        return $this->stringRoute;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getMiddlewareGroup(): array
    {
        return $this->middlewareGroup;
    }

    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            $this->middleware = $middleware;
        } else {
            $this->middleware[] = $middleware;
        }
        return $this;
    }

    public function group($middleware)
    {
        if (is_array($middleware)) {
            $this->middlewareGroup = $middleware;
        } else {
            $this->middlewareGroup[] = $middleware;
        }
        return $this;
    }
}