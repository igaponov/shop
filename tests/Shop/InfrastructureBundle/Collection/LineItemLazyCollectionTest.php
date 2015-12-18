<?php

namespace Tests\Shop\InfrastructureBundle\Collection;

use Shop\Domain\Cart\LineItemView;
use Shop\Domain\Product\ProductViewRepository;
use Shop\InfrastructureBundle\Collection\LineItemLazyCollection;

class LineItemLazyCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializedGetProductCountReturnsSumOfProductsQuantity()
    {
        $item1 = $this->getMock(LineItemView::class, ['getQuantity']);
        $item2 = $this->getMock(LineItemView::class, ['getQuantity']);
        $item1->expects($this->once())->method('getQuantity')->willReturn(3);
        $item2->expects($this->once())->method('getQuantity')->willReturn(5);
        $repository = $this->getMock(ProductViewRepository::class);
        /** @var \PHPUnit_Framework_MockObject_MockObject|LineItemLazyCollection $collection */
        $collection = $this->getMock(
            LineItemLazyCollection::class,
            ['isInitialized', 'initialize'],
            [[$item1, $item2], $repository]
        );
        $collection->expects($this->once())->method('isInitialized')->willReturn(true);
        $this->assertSame(8, $collection->getProductCount());
    }

    public function testNonInitializedGetProductCountReturnsArraySum()
    {
        $repository = $this->getMock(ProductViewRepository::class);
        /** @var \PHPUnit_Framework_MockObject_MockObject|LineItemLazyCollection $collection */
        $collection = $this->getMock(
            LineItemLazyCollection::class,
            ['isInitialized'],
            [['xxx' => 4, 'yyy' => 6], $repository]
        );
        $this->assertSame(10, $collection->getProductCount());
    }
}