<?php

namespace Shop\Application\Command;

use Symfony\Component\HttpFoundation\Request;

class VerifyRequestCommand
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @inheritDoc
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}