<?php

namespace Shop\Domain\Order;

use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\Quantity;

class LineItem
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var Quantity
     */
    private $quantity;
    /**
     * @var Money
     */
    private $price;

    public function __construct(Product $product, Order $order, Quantity $quantity)
    {
        $this->product = $product;
        $this->order = $order;
        $this->quantity = $quantity;
        $this->price = $product->getPrice();
    }

    /**
     * @return Product
     */
    public function getProduct() : Product
    {
        return $this->product;
    }

    /**
     * @return Order
     */
    public function getOrder() : Order
    {
        return $this->order;
    }

    /**
     * @return Quantity
     */
    public function getQuantity() : Quantity
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

    /**
     * @return Money
     */
    public function getPrice() : Money
    {
        return $this->price;
    }
}
