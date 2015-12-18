<?php

namespace Shop\InfrastructureBundle\Exception;

use Exception;
use RuntimeException;

class EntityNotFoundException extends RuntimeException
{
    /**
     * @inheritDoc
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Entity not found', 0, $previous);
    }
}