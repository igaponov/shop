<?php

namespace Tests\Shop\InfrastructureBundle\Validator;

use Shop\Domain\Product\ProductViewRepository;
use Shop\InfrastructureBundle\Validator\IsAvailable;
use Shop\InfrastructureBundle\Validator\IsAvailableValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

class IsAvailableValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ProductViewRepository $repository */
        $repository = $this->getMock(ProductViewRepository::class, []);
        $repository->expects($this->once())->method('getAvailability')->willReturnArgument(0);

        return new IsAvailableValidator($repository);
    }

    public function testIsValidWhenAvailable()
    {
        $this->validator->validate(true, new IsAvailable());

        $this->assertNoViolation();
    }

    public function testIsInvalidWhenNotAvailable()
    {
        $this->validator->validate(false, new IsAvailable());

        $this->buildViolation('This product is not available for order')->assertRaised();
    }
}