<?php

namespace Shop\Application\Event;

use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Quantity;

class ProductAddedEvent
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Quantity
     */
    private $quantity;

    /**
     * @inheritDoc
     */
    public function __construct(Product $product, Quantity $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return Quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}