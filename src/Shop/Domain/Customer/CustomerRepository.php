<?php

namespace Shop\Domain\Customer;

use Shop\Domain\ValueObject\UuidIdentity;

interface CustomerRepository
{
    /**
     * @param UuidIdentity $id
     * @return CustomerInterface
     */
    public function findByIdentity(UuidIdentity $id) : CustomerInterface;

    /**
     * @param CustomerInterface $customer
     * @return void
     */
    public function save(CustomerInterface $customer);

    /**
     * @param CustomerInterface $customer
     * @return void
     */
    public function remove(CustomerInterface $customer);
}