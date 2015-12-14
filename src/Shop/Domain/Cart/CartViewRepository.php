<?php

namespace Shop\Domain\Cart;

interface CartViewRepository
{
    /**
     * @return CartView
     */
    public function getCart() : CartView;
}