<?php

namespace Shop\Domain\Cart;

interface CartRepository
{
    /**
     * @return Cart
     */
    public function getCart(): Cart;

    /**
     * @param Cart $cart
     * @return void
     */
    public function setCart(Cart $cart);

    /**
     * @return void
     */
    public function clearCart();
}