<?php
/**
 * Copyright (c) 2019 Adrian Schubek
 * https://adriansoftware.de
 */

namespace adrianschubek\Routing;


class CachedRouter
{
    protected $router;

    /**
     * CachedRouter constructor.
     * @param $router
     */
    public function __construct(Rout $router)
    {
        $this->router = $router;
    }


}