<?php

namespace Shop\Application\Event;

use Shop\Domain\Order\Order;

class OrderCreatedEvent
{
    /**
     * @var Order
     */
    private $order;
    /**
     * @var string
     */
    private $gatewayName;

    /**
     * @inheritDoc
     */
    public function __construct(Order $order, \string $gatewayName)
    {
        $this->order = $order;
        $this->gatewayName = $gatewayName;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }
}