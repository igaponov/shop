<?php

namespace Tests\Shop\Domain\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Shop\Domain\Cart\Cart;
use Shop\Domain\ValueObject\Quantity;
use Tests\Shop\Domain\DomainTestCase;

class CartTest extends DomainTestCase
{
    public function testAddDifferentProducts()
    {
        $uuid1 = $this->getUuidMock(['getValue']);
        $uuid1->expects($this->once())->method('getValue')->willReturn('xxx');
        $product1 = $this->getProductMock(['getId']);
        $product1->expects($this->once())->method('getId')->willReturn($uuid1);
        $uuid2 = $this->getUuidMock(['getValue']);
        $uuid2->expects($this->once())->method('getValue')->willReturn('yyy');
        $product2 = $this->getProductMock(['getId']);
        $product2->expects($this->once())->method('getId')->willReturn($uuid2);
        $cart = new Cart(new ArrayCollection());
        $cart->addProduct($product1, new Quantity(2));
        $cart->addProduct($product2, new Quantity(4));
        $collection = $cart->getLineItems();
        $this->assertCount(2, $collection);
        $this->assertSame($product1, $collection->get('xxx')->getProduct());
        $this->assertSame($product2, $collection->get('yyy')->getProduct());
    }

    public function testAddSameProducts()
    {
        $uuid1 = $this->getUuidMock(['getValue']);
        $uuid1->expects($this->exactly(2))->method('getValue')->willReturn('xxx');
        $product1 = $this->getProductMock(['getId']);
        $product1->expects($this->exactly(2))->method('getId')->willReturn($uuid1);
        $cart = new Cart(new ArrayCollection());
        $cart->addProduct($product1, new Quantity(2));
        $cart->addProduct($product1, new Quantity(4));
        $collection = $cart->getLineItems();
        $this->assertCount(1, $collection);
        $this->assertSame($product1, $collection->get('xxx')->getProduct());
        $this->assertSame(6, $collection->get('xxx')->getQuantity()->getValue());
    }
}