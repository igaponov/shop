<?php

namespace Shop\Domain\Cart;

class CartView
{
    /**
     * @var LineItemView[]|LineItemCollection
     */
    private $lineItems;

    /**
     * @inheritDoc
     */
    public function __construct(LineItemCollection $lineItems = null)
    {
        $this->lineItems = $lineItems;
    }

    /**
     * @return LineItemView[]|LineItemCollection
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    public function getTotal()
    {
        $total = 0;
        $this->lineItems->map(
            function (LineItemView $lineItem) use (&$total) {
                $total += $lineItem->getTotal();
            }
        );

        return $total;
    }

    public function getCount()
    {
        return $this->lineItems->getProductCount();
    }
}