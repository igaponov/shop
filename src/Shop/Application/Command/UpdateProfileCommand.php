<?php

namespace Shop\Application\Command;

use Shop\Application\Model\Address;

class UpdateProfileCommand
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @inheritDoc
     */
    public function __construct(Address $address = null)
    {
        $this->address = $address;
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