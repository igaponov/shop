<?php

namespace Shop\Domain\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Shop\Domain\Customer\CustomerInterface;
use Shop\Domain\Product\Product;
use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\Money;
use Shop\Domain\ValueObject\Quantity;
use Shop\Domain\ValueObject\UuidIdentity;

class Order
{
    /**
     * @var UuidIdentity
     */
    private $id;
    /**
     * @var CustomerInterface
     */
    private $customer;
    /**
     * @var Address
     */
    private $address;
    /**
     * @var \DateTime|null
     */
    private $paidAt;
    /**
     * @var Collection|LineItem[]
     */
    protected $lineItems;

    public function __construct(UuidIdentity $id, CustomerInterface $customer, Address $address)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->address = $address;
        $this->lineItems = new ArrayCollection();
    }

    /**
     * @return UuidIdentity
     */
    public function getId() : UuidIdentity
    {
        return $this->id;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer() : CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @return Address
     */
    public function getAddress() : Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function changeAddress(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return \DateTime|null
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    public function markAsPaid()
    {
        $this->paidAt = new \DateTime();
    }

    /**
     * @param Product $product
     * @param Quantity $quantity
     * @return Order
     */
    public function addProduct(Product $product, Quantity $quantity) : Order
    {
        if (!$product->isAvailable()) {
            throw new \RuntimeException(sprintf('Product #%s is not available', $product->getId()));
        }
        $lineItem = new LineItem($product, $this, $quantity);
        $this->lineItems->add($lineItem);

        return $this;
    }

    /**
     * @return Collection|LineItem[]
     */
    public function getLineItems() : Collection
    {
        return $this->lineItems;
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        $total = new Money(0);
        $this->lineItems->map(
            function (LineItem $lineItem) use (&$total) {
                $total = $total->add($lineItem->getPrice());
            }
        );

        return $total;
    }
}
