<?php

namespace Shop\Domain\Customer;

interface CustomerViewRepository
{
    const LIMIT = 10;

    /**
     * @param int $page
     * @param array $orderBy
     * @param int $limit
     * @return array|CustomerView[]
     */
    public function getAll(\int $page = 1, array $orderBy = [], \int $limit = self::LIMIT) : array;

    /**
     * @param string $id
     * @return CustomerView
     */
    public function getById(\string $id) : CustomerView;

    /**
     * @param CustomerView $view
     */
    public function save(CustomerView $view);
}