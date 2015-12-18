<?php

namespace Shop\InfrastructureBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;
use Shop\Domain\Category\Category;
use Shop\Domain\Category\CategoryRepository as BaseCategoryRepository;
use Shop\Domain\ValueObject\UuidIdentity;
use Shop\InfrastructureBundle\Exception\EntityNotFoundException;

class CategoryRepository extends EntityRepository implements BaseCategoryRepository
{
    /**
     * @inheritdoc
     */
    public function findByIdentity(UuidIdentity $id) : Category
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
    public function save(Category $category)
    {
        $this->getEntityManager()->persist($category);
    }

    /**
     * @inheritdoc
     */
    public function remove(Category $category)
    {
        $this->getEntityManager()->remove($category);
    }

    /**
     * @inheritdoc
     */
    public function getReference(UuidIdentity $id): Category
    {
        return $this->getEntityManager()->getPartialReference(Category::class, $id);
    }
}