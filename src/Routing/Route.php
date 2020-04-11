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
    protected string $customRegex = "([a-zA-Z0-9_-]+)";
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

    private function regex(string $route): string
    {
        $str = preg_quote($route, '/');
        $str = str_replace("\]", "]", $str);
        $str = str_replace("/\\", "/", $str);
        $str = preg_replace("/\[([a-z]+)]/", $this->customRegex, $str);
        return $str;
    }

    public function where(string $regex): self
    {
        $this->customRegex = $regex;
        $this->route = $this->regex($this->stringRoute);
        return $this;
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

    public function middleware($middleware): self
    {
        if (is_array($middleware)) {
            $this->middleware = $middleware;
        } else {
            $this->middleware[] = $middleware;
        }
        return $this;
    }

    public function group($middleware): self
    {
        if (is_array($middleware)) {
            $this->middlewareGroup = $middleware;
        } else {
            $this->middlewareGroup[] = $middleware;
        }
        return $this;
    }
}