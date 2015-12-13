<?php

namespace Tests\Shop\Domain;

use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\UuidIdentity;

abstract class DomainTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject|UuidIdentity
     */
    protected function getUuidMock($methods = [])
    {
        return $this->getMockBuilder(UuidIdentity::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getProductMock($methods = [])
    {
        return $this->getMockBuilder(Product::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }
}