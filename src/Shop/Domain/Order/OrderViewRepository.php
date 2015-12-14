<?php

namespace Shop\Domain\Order;

interface OrderViewRepository
{
    const LIMIT = 10;

    /**
     * @param int $page
     * @param array $orderBy
     * @param int $limit
     * @return array|OrderView[]
     */
    public function getAll(\int $page = 1, array $orderBy = [], \int $limit = self::LIMIT): array;

    /**
     * @param string $id
     * @return OrderView
     */
    public function getById(\string $id) : OrderView;

    /**
     * @param string $customerId
     * @return array
     */
    public function getByCustomer(\string $customerId): array;
}