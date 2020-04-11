<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;


class CachedRouter
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }


}