<?php

namespace Shop\InfrastructureBundle\UrlCollector;

interface UrlCollectorInterface
{
    /**
     * Returns all public urls for GET requests
     *
     * @return \Iterator
     */
    public function collect(): \Iterator;
}