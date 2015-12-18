<?php

namespace Shop\InfrastructureBundle\Validator;

use Symfony\Component\Validator\Constraint;

class IsAvailable extends Constraint
{
    public $message = 'This product is not available for order';

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return 'is_available_validator';
    }
}