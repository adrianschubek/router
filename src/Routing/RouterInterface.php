<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;


interface RouterInterface
{
    function get(string $routePath, $controller);

    function post(string $routePath, $controller);

    function put(string $routePath, $controller);

    function patch(string $routePath, $controller);

    function delete(string $routePath, $controller);

    function options(string $routePath, $controller);
}