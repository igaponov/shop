<?php

namespace Shop\Domain\Cart;

use Shop\Domain\Product\ProductView;

class LineItemView
{
    /**
     * @var ProductView
     */
    private $product;
    /**
     * @var int
     */
    private $quantity;

    public static function create(ProductView $product, int $quantity)
    {
        $lineItemView = new static;
        $lineItemView->product = $product;
        $lineItemView->quantity = $quantity;

        return $lineItemView;
    }

    /**
     * @return ProductView
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->quantity * $this->product->getPrice();
    }
}