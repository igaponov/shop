<?php

namespace Tests\Shop\Domain\Product;

use Shop\Domain\Category\Category;
use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\UuidIdentity;
use Tests\Shop\Domain\DomainTestCase;

class ProductTestCase extends DomainTestCase
{
    public function testConstruct()
    {
        $uuid = $this->getUuidMock();
        $name = 'test product';
        $price = new Money(10000);
        $category = new Category(new UuidIdentity('uuid2'), 'test cat');
        $description = 'description';
        $availability = true;

        $product = new Product($uuid, $name, $price, $category, $description, $availability, '/');

        $this->assertSame($uuid, $product->getId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($category, $product->getCategory());
        $this->assertSame($description, $product->getDescription());
        $this->assertTrue($product->isAvailable());
    }
}