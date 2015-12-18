<?php

namespace Shop\InfrastructureBundle\Repository\DBAL;

use Doctrine\DBAL\Connection;
use Shop\Domain\Order\LineItemView;
use Shop\Domain\Order\OrderView;
use Shop\Domain\Order\OrderViewRepository as BaseOrderViewRepository;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class OrderViewRepository implements BaseOrderViewRepository
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @inheritDoc
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @inheritdoc
     */
    public function getAll(\int $page = 1, array $orderBy = [], \int $limit = self::LIMIT) : array
    {
        $query = $this->getBaseQuery()
            ->where('t.paid_at')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        foreach ($orderBy as $sort => $order) {
            $query->addOrderBy($sort, $order);
        }

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, OrderView::class);

        return $statement->fetchAll();
    }

    /**
     * @param string $id
     * @return OrderView
     */
    public function getById(\string $id) : OrderView
    {
        $query = $this->getBaseQuery()
            ->where('t.id = :id')
            ->setParameter(':id', $id, \PDO::PARAM_STR);

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, OrderView::class);
        /** @var OrderView $order */
        $order = $statement->fetch();

        if (!$order) {
            throw new EntityNotFoundException;
        }

        $lineItems = $this->fetchLineItems($order->getId());
        foreach ($lineItems as $lineItem) {
            $order->addLineItem($lineItem);
        }

        return $order;
    }

    /**
     * @inheritDoc
     */
    public function getByCustomer(\string $customerId): array
    {
        $query = $this->getBaseQuery()
            ->where('t.customer_id = :customer_id')
            ->setParameter('customer_id', $customerId);

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, OrderView::class);
        /** @var OrderView[] $orders */
        $orders = $statement->fetchAll();

        if ($orders) {
            $ids = array_map(
                function (OrderView $orderView) {
                    return (string)$orderView->getId();
                },
                $orders
            );
            $lineItems = $this->fetchLineItems($ids);

            foreach ($orders as &$order) {
                if (isset($lineItems[$order->getId()])) {
                    foreach ($lineItems[$order->getId()] as &$lineItem) {
                        $order->addLineItem($lineItem);
                    }
                }
            }
        }

        return $orders;
    }

    protected function fetchLineItems($ids)
    {
        $connection = $this->conn;
        $expr = $connection->getExpressionBuilder();
        $query = $connection->createQueryBuilder()
            ->select('t.order_id, p.id, p.name, t.quantity, t.price')
            ->from('line_items', 't')
            ->join('t', 'products', 'p', 't.product_id = p.id');
        if (is_array($ids)) {
            $query->where($expr->in('t.order_id', array_fill(0, count($ids), '?')))
                ->setParameters($ids);
        } else {
            $query->where('t.order_id = :order')
                ->setParameter('order', $ids);
        }

        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, LineItemView::class);
        return $statement->fetchAll(is_array($ids) ? \PDO::FETCH_GROUP : null);
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private function getBaseQuery()
    {
        return $this->conn->createQueryBuilder()
            ->select('t.id, t.customer_id as customerId, t.country, t.city, t.street, t.zip_code as zipCode')
            ->from('orders', 't');
    }
}