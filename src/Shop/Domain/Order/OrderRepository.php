<?php

namespace Shop\Domain\Order;

use Shop\Domain\ValueObject\UuidIdentity;

interface OrderRepository
{
    /**
     * @param UuidIdentity $id
     * @return Order
     */
    public function findByIdentity(UuidIdentity $id): Order;

    /**
     * @param Order $order
     * @return void
     */
    public function save(Order $order);

    /**
     * @param Order $order
     * @return void
     */
    public function remove(Order $order);
}