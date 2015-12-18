<?php

namespace Shop\InfrastructureBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;
use Shop\Domain\Customer\CustomerInterface;
use Shop\Domain\Customer\CustomerRepository as BaseCustomerRepository;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class CustomerRepository extends EntityRepository implements BaseCustomerRepository
{
    /**
     * @inheritdoc
     */
    public function findByIdentity(UuidIdentity $id) : CustomerInterface
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
    public function save(CustomerInterface $customer)
    {
        $this->getEntityManager()->persist($customer);
    }

    /**
     * @inheritdoc
     */
    public function remove(CustomerInterface $customer)
    {
        $this->getEntityManager()->remove($customer);
    }
}