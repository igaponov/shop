<?php

namespace Shop\InfrastructureBundle\Repository\Session;

use Shop\Domain\Cart\CartView;
use Shop\Domain\Cart\CartViewRepository as BaseActiveOrderViewRepository;
use Shop\Domain\Product\ProductViewRepository;
use Shop\InfrastructureBundle\Collection\LineItemLazyCollection;
use Symfony\Component\HttpFoundation\Session\Session;

class CartViewRepository implements BaseActiveOrderViewRepository
{
    const NAME = 'order';

    /**
     * @var Session
     */
    private $session;
    /**
     * @var ProductViewRepository
     */
    private $repository;

    public function __construct(Session $session, ProductViewRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * @return CartView
     */
    public function getCart(): CartView
    {
        $lineItems = $this->session->get(self::NAME, []);
        $cart = new CartView(new LineItemLazyCollection($lineItems, $this->repository));

        return $cart;
    }
}