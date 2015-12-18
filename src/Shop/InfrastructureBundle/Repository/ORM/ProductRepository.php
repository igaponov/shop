<?php

namespace Shop\InfrastructureBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;
use Shop\Domain\Product\Product;
use Shop\Domain\Product\ProductRepository as BaseProductRepository;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class ProductRepository extends EntityRepository implements BaseProductRepository
{
    /**
     * @inheritdoc
     */
    public function findByIdentity(UuidIdentity $id) : Product
    {
        $result = $this->find($id);

        if (!$result) {
            throw new EntityNotFoundException;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function findByIdentities(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $builder = $this->getEntityManager()->createQueryBuilder();

        return $builder
            ->select('t')
            ->from($this->getEntityName(), 't')
            ->where($builder->expr()->in('t.id.value', $ids))
            ->getQuery()->execute();
    }

    /**
     * @inheritdoc
     */
    public function save(Product $product)
    {
        $this->getEntityManager()->persist($product);
    }

    /**
     * @inheritdoc
     */
    public function remove(Product $product)
    {
        $this->getEntityManager()->remove($product);
    }
}