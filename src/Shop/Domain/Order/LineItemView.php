<?php

namespace Shop\Domain\Order;

class LineItemView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $quantity;
    /**
     * @var int
     */
    private $price;

    public static function create(string $id, string $name, int $quantity, int $price)
    {
        $lineItemView = new static;
        $lineItemView->id = $id;
        $lineItemView->name = $name;
        $lineItemView->quantity = $quantity;
        $lineItemView->price = $price;

        return $lineItemView;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getPrice()
    {
        return $this->price;
    }

    public function getTotal()
    {
        return $this->price * $this->quantity;
    }
}