<?php

namespace Shop\InfrastructureBundle\Validator;

use Shop\Domain\Product\ProductViewRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsAvailableValidator extends ConstraintValidator
{
    /**
     * @var ProductViewRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(ProductViewRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param string $value The value that should be validated
     * @param IsAvailable|Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $available = $this->repository->getAvailability($value);
        if (!$available) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}