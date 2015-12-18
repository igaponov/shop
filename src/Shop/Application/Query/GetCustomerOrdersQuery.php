<?php

namespace Shop\Application\Query;

class GetCustomerOrdersQuery extends AbstractQuery
{
    /**
     * @var string
     */
    private $customerId;

    /**
     * @inheritDoc
     */
    public function __construct(\string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
}