<?php

namespace Shop\InfrastructureBundle\Repository\Session;

use Doctrine\Common\Collections\ArrayCollection;
use Shop\Domain\Cart\Cart;
use Shop\Domain\Cart\CartRepository as BaseActiveOrderRepository;
use Shop\Domain\Cart\LineItem;
use Shop\Domain\Product\ProductRepository;
use Shop\Domain\ValueObject\Quantity;
use Symfony\Component\HttpFoundation\Session\Session;

class CartRepository implements BaseActiveOrderRepository
{
    const NAME = 'order';

    /**
     * @var Session
     */
    private $session;
    /**
     * @var ProductRepository
     */
    private $repository;

    public function __construct(Session $session, ProductRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getCart(): Cart
    {
        $data = $this->session->get(self::NAME, []);
        $lineItems = new ArrayCollection();
        $products = $this->repository->findByIdentities(array_keys($data));
        foreach ($products as $product) {
            $lineItem = new LineItem($product, new Quantity($data[$product->getId()->getValue()]));
            $lineItems->set($product->getId()->getValue(), $lineItem);
        }

        return new Cart($lineItems);
    }

    /**
     * @inheritdoc
     */
    public function setCart(Cart $cart)
    {
        $lineItems = $cart->getLineItems()->map(
            function (LineItem $lineItem) {
                return $lineItem->getQuantity()->getValue();
            }
        );
        $this->session->set(self::NAME, $lineItems->toArray());
    }

    /**
     * @inheritDoc
     */
    public function clearCart()
    {
        $this->session->remove(self::NAME);
    }
}