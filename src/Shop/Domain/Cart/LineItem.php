<?php

namespace Shop\Domain\Cart;

use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Quantity;

class LineItem
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

    /**
     * @param Quantity $quantity
     */
    public function changeQuantity(Quantity $quantity)
    {
        $this->quantity = $quantity;
    }
}