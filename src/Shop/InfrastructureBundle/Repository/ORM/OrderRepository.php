<?php

namespace Shop\InfrastructureBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;
use Shop\Domain\Order\Order;
use Shop\Domain\Order\OrderRepository as BaseOrderRepository;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class OrderRepository extends EntityRepository implements BaseOrderRepository
{
    /**
     * @inheritdoc
     */
    public function findByIdentity(UuidIdentity $id): Order
    {
        $result = $this->find($id);

        if (!$result) {
            throw new EntityNotFoundException;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function save(Order $order)
    {
        $this->getEntityManager()->persist($order);
    }

    /**
     * @inheritdoc
     */
    public function remove(Order $order)
    {
        $this->getEntityManager()->remove($order);
    }
}