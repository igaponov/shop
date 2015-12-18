<?php

namespace Shop\Application\Command;

use Shop\Application\Model\Address;

class CreateOrderCommand
{
    /**
     * @var Address
     */
    private $address;
    /**
     * @var string
     */
    private $gatewayName;

    /**
     * @inheritDoc
     */
    public function __construct(
        Address $address,
        string $gatewayName = ''
    )
    {
        $this->address = $address;
        $this->gatewayName = $gatewayName;
    }

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    /**
     * @param string $gatewayName
     */
    public function setGatewayName($gatewayName)
    {
        $this->gatewayName = $gatewayName;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}