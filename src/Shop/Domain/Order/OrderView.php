<?php

namespace Shop\Domain\Order;

class OrderView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $customerId;
    /**
     * @var array|LineItemView[]
     */
    private $lineItems;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $street;
    /**
     * @var string
     */
    private $zipCode;
    /**
     * @var string
     */
    private $paidAt;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return substr($this->id, 0, 6);
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return array|LineItemView[]
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    public function addLineItem(LineItemView $lineItem)
    {
        $this->lineItems[] = $lineItem;
    }

    public function addProduct(string $productId, string $productName, int $quantity, int $price)
    {
        array_filter($this->lineItems, function(LineItemView $lineItem) use($productId, $price) {
            return $lineItem->getId() === $productId
                && $lineItem->getId();
        });
        $lineItem = LineItemView::create($productId, $productName, $quantity, $price);
        $this->lineItems[] = $lineItem;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $total = 0;
        array_map(
            function (LineItemView $lineItem) use (&$total) {
                $total += $lineItem->getTotal();
            },
            $this->lineItems
        );

        return $total;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }
}