<?php

namespace Shop\Domain\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Quantity;
use Shop\Domain\ValueObject\UuidIdentity;

class Cart
{
    /**
     * @var LineItem[]|Collection
     */
    private $lineItems;

    /**
     * @inheritDoc
     */
    public function __construct(ArrayCollection $lineItems)
    {
        $this->lineItems = $lineItems;
    }

    /**
     * @return Collection|LineItem[]
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param Product $product
     * @param Quantity $quantity
     * @return Cart
     */
    public function addProduct(Product $product, Quantity $quantity): Cart
    {
        $key = $product->getId()->getValue();
        /** @var LineItem $lineItem */
        $lineItem = $this->lineItems->get($key);
        if ($lineItem === null) {
            $lineItem = new LineItem($product, $quantity);
        } else {
            $lineItem = new LineItem($product, $lineItem->getQuantity()->add($quantity));
        }
        $this->lineItems->set($key, $lineItem);

        return $this;
    }

    public function removeProduct(UuidIdentity $id)
    {
        $element = $this->lineItems->filter(function(LineItem $lineItem) use($id) {
            return $lineItem->getProduct()->getId()->getValue() === $id->getValue();
        })->first();
        if ($element) {
            $this->lineItems->removeElement($element);
        }

        return $this;
    }
}