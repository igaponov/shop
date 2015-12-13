<?php

namespace Shop\Domain\Customer;

use Shop\Domain\ValueObject\Address;
use Shop\Domain\ValueObject\UuidIdentity;

interface CustomerInterface
{
    /**
     * @return UuidIdentity
     */
    public function getId();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return Address
     */
    public function getAddress();
}