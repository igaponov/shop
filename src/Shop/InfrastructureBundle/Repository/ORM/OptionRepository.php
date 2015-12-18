<?php

namespace Shop\InfrastructureBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;
use Shop\Domain\Option\Option;
use Shop\Domain\Option\OptionRepository as BaseOptionRepository;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class OptionRepository extends EntityRepository implements BaseOptionRepository
{
    /**
     * @inheritdoc
     */
    public function findByIdentity(UuidIdentity $id) : Option
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
    public function save(Option $option)
    {
        $this->getEntityManager()->persist($option);
    }

    /**
     * @inheritdoc
     */
    public function remove(Option $option)
    {
        $this->getEntityManager()->remove($option);
    }

    /**
     * @inheritdoc
     */
    public function getReference(UuidIdentity $id): Option
    {
        return $this->getEntityManager()->getPartialReference(Option::class, $id);
    }
}