<?php

namespace Shop\Application\Event;

use Shop\Domain\Product\Product;

class ProductSavedEvent
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @inheritDoc
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}