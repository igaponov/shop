<?php

namespace Shop\Application\Command;

class AddProductCommand
{
    /**
     * @var string
     */
    private $productId;
    /**
     * @var int
     */
    private $quantity;

    public function __construct(\string $productId, \int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}